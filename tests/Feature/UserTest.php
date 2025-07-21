<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_are_ranked_by_total_post_views()
    {
        $users = User::factory()->count(3)->create();

        Post::factory()->create(['user_id' => $users[0]->id, 'view_count' => 5]);
        Post::factory()->create(['user_id' => $users[1]->id, 'view_count' => 10]);
        Post::factory()->create(['user_id' => $users[2]->id, 'view_count' => 3]);

        $token = auth('api')->login($users[0]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['id', 'mobile', 'avatar', 'total_views']
                ],
                'server_time',
            ])
            ->assertJsonPath('data.0.id', $users[1]->id)
            ->assertJsonPath('data.0.total_views', 10)
            ->assertJsonCount(3, 'data');
    }
}
