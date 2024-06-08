<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{   

    use RefreshDatabase;

    public function test_user_can_login(): void
    {
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')]);

        $response = $this->postJson('/graphql',[
            'query' => '
                mutation login($email: String!, $password: String!) {
                    login(email: $email, password: $password) {
                        access_token
                        email
                        name
                        token_type
                    }
                }
            ',

            'variables' => [
                'email' => 'test@example.com',
                'password' => 'password'
            ]
        ]);

        $data = json_decode($response->getContent(), true);

        $response->assertJson([
            'data' => [
                'login' => [
                    'access_token' => $data['data']['login']['access_token'],
                    'email' => $user->email,
                    'name' => $data['data']['login']['name'],
                    'token_type' => 'Bearer'
                ]
            ]
        ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->postJson('/graphql', [
            'query' => '
                mutation logout {
                    logout
                }
            ',
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);


        $response->assertJson([
            'data' => [
                'logout' => true
            ]
        ]);
    }
}
