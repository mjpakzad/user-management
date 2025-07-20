<?php


namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('posts', [
            'id', 'user_id', 'heading', 'content', 'view_count', 'created_at', 'updated_at',
        ]));
    }
}
