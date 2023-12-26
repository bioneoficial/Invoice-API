<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserRegistrationAndLoginTest extends TestCase
{
    public function testRegistrationAndLogin()
    {
        $userData = User::factory()->make()->only(['name', 'email', 'password']);

        $this->postJson(route('register'), $userData)->assertStatus(201);

        $this->postJson(route('login'), $userData)->assertStatus(200);
    }
}
