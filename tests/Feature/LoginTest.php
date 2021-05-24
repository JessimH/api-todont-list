<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_no_inputs()
    {
        $response = $this->postJson('/api/auth/login');

        $response->assertStatus(422);
    }

    public function test_invalid_inputs()
    {
        $data = [
            'email' =>"jessotmail.com",
            'password' =>"azertyuiop",
            'device_name' => 'ios'
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(422);
    }

    public function test_invalid_credentials()
    {
        $data = [
            'email' =>"jessim@hotmail.com",
            'password' =>"azertyuiop",
            'device_name' => 'ios'
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(401);
    }
    
    public function test_login_with_success()
    {
        $data = [
            'email' =>"jesszerfim@test.fr",
            'password' =>"azertyuiop",
            'device_name' => 'ios'
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(200);
    }
}
