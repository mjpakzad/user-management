<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_successfully()
    {
        $response = $this->postJson('/api/v1/register', [
            'mobile'   => '09337557488',
            'password' => 'secret',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'        => ['access_token', 'token_type', 'expires_in'],
                'server_time',
            ]);
    }

    public function test_login_successfully()
    {
        $user = User::factory()->create([
            'mobile'   => '09337557488',
            'password' => bcrypt('secret'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'mobile'   => '09337557488',
            'password' => 'secret',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'        => ['access_token', 'token_type', 'expires_in'],
                'server_time',
            ]);
    }

    public function test_me_returns_user_data()
    {
        $user  = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'        => ['id', 'mobile', 'avatar', 'created_at'],
                'server_time',
            ])
            ->assertJsonPath('data.mobile', $user->mobile);
    }

    public function test_logout_successfully()
    {
        $user  = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'        => ['message'],
                'server_time',
            ])
            ->assertJsonPath('data.message', 'Successfully logged out');
    }

    public function test_refresh_token()
    {
        $user  = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'        => ['access_token', 'token_type', 'expires_in'],
                'server_time',
            ]);
    }
}
