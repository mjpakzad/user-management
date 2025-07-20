<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login',    [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout'])->name('logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::get('me',       [AuthController::class, 'me'])->name('me');

        Route::post('avatar',  [ProfileController::class, 'uploadAvatar'])->name('avatar.upload');

        Route::get('posts',       [PostController::class, 'index'])->name('posts.index');
        Route::get('posts/{post}',[PostController::class, 'show' ])->name('posts.show');
    });
});
