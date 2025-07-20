<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_avatar()
    {
        Storage::fake('public');

        $user  = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.jpg'),
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'        => ['message', 'avatar_url'],
                'server_time',
            ]);

        Storage::disk('public')->assertExists($user->fresh()->avatar);
    }

    public function test_avatar_validation_fails_with_no_file()
    {
        $user  = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/avatar', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('avatar', 'data.errors');
    }
}
