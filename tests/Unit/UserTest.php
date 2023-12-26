<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


class UserTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserRegistration()
    {
        $userData = ['name' => 'Test User', 'email' => User::factory()->make()->email, 'password' => 'password'];
        $response = $this->post(route('register'), $userData);
        $response->assertStatus(201);

        $this->assertDatabaseHas('users', ['name' => 'Test User', 'email' => $userData['email']]);
    }


    public function testCanGetUserAuth()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->get('/api/invoices');

        $response->assertStatus(200);
    }

    public function testCanUserLogin()
    {
        $user = User::factory()->create(['password' => Hash::make('password'),]);

        $response = $this->post(route('login'), ['email' => $user->email, 'password' => 'password',]);

        $response->assertStatus(200);

        $response->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'user' => ['_id', 'name', 'email', 'updated_at', 'created_at',]]);
    }

    public function testUserRegistrationWithExistingEmail()
    {
        $existingUser = User::factory()->create();

        $userData = ['name' => 'Test User', 'email' => $existingUser->email, 'password' => 'password',];

        $response = $this->post(route('register'), $userData);

        $response->assertStatus(400);
    }

    public function testUserLoginWithInvalidEmail()
    {
        $response = $this->post(route('login'), ['email' => 'nonexistent.user@example.com', 'password' => 'password',]);

        $response->assertStatus(401);
    }

    public function testUserLoginWithInvalidPassword()
    {
        $user = User::factory()->create(['password' => Hash::make('password'),]);

        $response = $this->post(route('login'), ['email' => $user->email, 'password' => 'invalidpassword',]);

        $response->assertStatus(401);
    }

    public function testRegistrationWithEmailAlreadyTaken(){
        for($i = 0; $i < 2; $i++){
            $userData = ['name' => 'Test User', 'email' => 'joao.bione@azapfy.com', 'password' => 'recrutanois'];
            $response = $this->post(route('register'), $userData);
            if($i == 0){
                $response->assertStatus(201);
            }
        }
        $response->assertStatus(400);
        $response->assertJson(['email' => ['The email has already been taken.']]);
    }
}
