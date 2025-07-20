<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_paginated_posts()
    {
        Redis::flushall();

        $user = User::factory()->create();
        Post::factory()->count(15)->create([
            'user_id'    => $user->id,
            'view_count' => 0,
        ]);

        $token = auth('api')->login($user);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/posts?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['heading','content','view_count','author' => ['id','mobile','avatar']]
                ],
                'server_time'
            ]);

        $this->assertCount(10, $response->json('data'));
    }

    public function test_user_can_get_single_post_and_increment_view_count()
    {
        Redis::flushall();

        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id'    => $user->id,
            'view_count' => 5,
        ]);

        $token = auth('api')->login($user);

        $resp1 = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->withServerVariables(['REMOTE_ADDR' => '1.1.1.1'])
            ->getJson("/api/v1/posts/{$post->id}");

        $resp1->assertStatus(200)
            ->assertJsonPath('data.view_count', 6);

        $resp2 = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->withServerVariables(['REMOTE_ADDR' => '1.1.1.1'])
            ->getJson("/api/v1/posts/{$post->id}");

        $resp2->assertJsonPath('data.view_count', 6);

        $resp3 = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->withServerVariables(['REMOTE_ADDR' => '2.2.2.2'])
            ->getJson("/api/v1/posts/{$post->id}");

        $resp3->assertJsonPath('data.view_count', 7);
    }
}
