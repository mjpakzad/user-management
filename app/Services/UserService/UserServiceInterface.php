<?php

namespace App\Services\UserService;

use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function paginateByTotalViews(int $perPage): LengthAwarePaginator;
}
