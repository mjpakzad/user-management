<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return compact('user', 'token');
    }

    public function login(array $credentials): string|false
    {
        return auth('api')->attempt($credentials);
    }

    public function getTokenTTL(): int
    {
        return config('jwt.ttl') * 60;
    }
}
