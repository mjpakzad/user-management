<?php

namespace App\Providers;

use App\Services\AuthService\AuthService;
use App\Services\AuthService\AuthServiceInterface;
use App\Services\PostService\PostService;
use App\Services\PostService\PostServiceInterface;
use App\Services\ProfileService\ProfileService;
use App\Services\ProfileService\ProfileServiceInterface;
use App\Services\UserService\UserService;
use App\Services\UserService\UserServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(ProfileServiceInterface::class, ProfileService::class);
        $this->app->singleton(PostServiceInterface::class, PostService::class);
        $this->app->singleton(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
