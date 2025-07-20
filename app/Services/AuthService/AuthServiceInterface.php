<?php

namespace App\Services\AuthService;

interface AuthServiceInterface
{
    public function register(array $data): array;
    public function login(array $credentials): array;
    public function logout(): array;
    public function refresh(): array;
    public function getTokenTTL(): int;
}
