<?php

namespace App\Services\PostService;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostServiceInterface
{
    public function paginateForUser(int $perPage): LengthAwarePaginator;

    public function getPost(Post $post, string $ip): Post;
}
