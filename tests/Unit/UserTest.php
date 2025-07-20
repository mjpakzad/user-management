<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id', 'mobile', 'password', 'avatar', 'created_at', 'updated_at',
        ]));
    }
}
