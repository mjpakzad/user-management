<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api_v1.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'data' => [
                    'message' => $e->getMessage(),
                    'errors'  => $e->errors(),
                ],
                'server_time' => now()->toISOString(),
            ], 422);
        });

        $exceptions->renderable(function (HttpResponseException|HttpExceptionInterface $e, $request) {
            $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
            return response()->json([
                'data' => [
                    'message' => $e->getMessage(),
                ],
                'server_time' => now()->toISOString(),
            ], $status);
        });

        $exceptions->renderable(function (\Throwable $e, $request) {
            return response()->json([
                'data' => [
                    'message' => 'Internal server error.',
                ],
                'server_time' => now()->toISOString(),
            ], 500);
        });
    })->create();
