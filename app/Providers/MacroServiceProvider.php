<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('api', function ($payload = []) {
            return response()->json([
                'data' => $payload,
                'server_time' => now()->toISOString(),
            ]);
        });
    }
}
