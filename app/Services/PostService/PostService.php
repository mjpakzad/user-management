<?php

namespace App\Services\PostService;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class PostService implements PostServiceInterface
{
    protected int $threshold;

    public function __construct()
    {
        $this->threshold = config('posts.view_flush_threshold', 10);
        $this->ipRecordTtl = config('posts.ip_record_ttl');
    }

    public function paginateForUser(int $perPage): LengthAwarePaginator
    {
        $paginator = Post::with('user')->paginate($perPage);

        $paginator->getCollection()->transform(function (Post $post) {
            $key   = "post:{$post->id}:views";
            $count = Redis::get($key);
            $post->view_count = $count !== null
                ? (int)$count
                : $post->view_count;
            return $post;
        });

        return $paginator;
    }

    public function getPost(Post $post, string $ip): Post
    {
        $id         = $post->id;
        $keyIps     = "post:{$id}:ips";
        $keyCount   = "post:{$id}:views";
        $keyPending = "post:{$id}:views:pending";

        if (Redis::exists($keyCount) == 0) {
            Redis::set($keyCount, $post->view_count);
        }
        if (Redis::exists($keyPending) == 0) {
            Redis::set($keyPending, 0);
        }

        if (Redis::sadd($keyIps, $ip)) {
            Redis::incr($keyCount);
            Redis::incr($keyPending);
            Redis::expire($keyIps, $this->ipRecordTtl);
        }

        $pending = (int) Redis::get($keyPending);
        if ($pending >= $this->threshold) {
            DB::table('posts')
                ->where('id', $id)
                ->increment('view_count', $pending);
            Redis::decrby($keyPending, $pending);
        }

        $post->view_count = (int) Redis::get($keyCount);

        return $post->load('user');
    }
}
