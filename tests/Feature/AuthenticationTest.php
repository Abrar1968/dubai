<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/*
|--------------------------------------------------------------------------
| Authentication Tests
|--------------------------------------------------------------------------
*/

test('login page is accessible', function () {
    $response = $this->get('/login');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('auth/Login')
    );
});

test('register page is accessible', function () {
    $response = $this->get('/register');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('auth/Register')
    );
});

test('forgot password page is accessible', function () {
    $response = $this->get('/forgot-password');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('auth/ForgotPassword')
    );
});

test('users can login with valid credentials', function () {
    $user = User::factory()->withoutTwoFactor()->create([
        'email' => 'test@example.com',
    ]);
    
    $response = $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);
    
    $this->assertAuthenticated();
});

test('users cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);
    
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);
    
    $this->assertGuest();
});

test('users can register', function () {
    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    
    $this->assertAuthenticated();
    
    $this->assertDatabaseHas('users', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
    ]);
});

test('registration validates required fields', function () {
    $response = $this->post('/register', [
        'name' => '',
        'email' => '',
        'password' => '',
        'password_confirmation' => '',
    ]);
    
    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

test('registration validates unique email', function () {
    User::factory()->create([
        'email' => 'existing@example.com',
    ]);
    
    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    
    $response->assertSessionHasErrors(['email']);
});

test('authenticated users can logout', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user);
    $this->assertAuthenticated();
    
    $response = $this->post('/logout');
    
    $this->assertGuest();
});
