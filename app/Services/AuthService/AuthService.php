<?php

namespace App\Services\AuthService;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    public function register(array $data): array
    {
        $user = User::query()->create([
            'mobile'   => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);

        return $this->respondWithToken(JWTAuth::fromUser($user));
    }

    public function login(array $credentials): array
    {
        $token = JWTAuth::attempt($credentials);

        if (! $token) {
            abort(401, 'Invalid credentials');
        }

        return $this->respondWithToken($token);
    }

    public function logout(): array
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return ['message' => 'Successfully logged out'];
    }

    public function refresh(): array
    {
        $newToken = JWTAuth::parseToken()->refresh();
        return $this->respondWithToken($newToken);
    }

    public function getTokenTTL(): int
    {
        return config('jwt.ttl') * 60;
    }

    protected function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ];
    }
}
