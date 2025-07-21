<?php

namespace App\Services\UserService;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService implements UserServiceInterface
{
    public function paginateByTotalViews(int $perPage): LengthAwarePaginator
    {
        $query = User::query()
            ->select('users.*', DB::raw('COALESCE(SUM(posts.view_count),0) AS total_views'))
            ->leftJoin('posts', 'posts.user_id', '=', 'users.id')
            ->groupBy('users.id')
            ->orderByDesc('total_views');

        return $query->paginate($perPage);
    }
}
