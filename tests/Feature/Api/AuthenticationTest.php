<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('user can register as creator', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test Creator',
        'email' => 'testcreator@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'creator',
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['token', 'user']);

    $this->assertDatabaseHas('users', [
        'email' => 'testcreator@example.com',
    ]);
});

test('user can register as customer', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test Customer',
        'email' => 'testcustomer@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'customer',
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['token', 'user']);
});

test('user can login with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'creator@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'creator@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure(['token', 'user']);
});

test('login fails with wrong password', function () {
    $user = User::factory()->create([
        'email' => 'creator@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'creator@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
             ->assertJson(['message' => 'Invalid credentials']);
});