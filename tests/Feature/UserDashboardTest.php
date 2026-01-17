<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/*
|--------------------------------------------------------------------------
| User Dashboard Routes Tests
|--------------------------------------------------------------------------
*/

test('guests are redirected from user dashboard', function () {
    $response = $this->get('/user/dashboard');
    
    $response->assertRedirect(route('login'));
});

test('authenticated users can access user dashboard', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/user/dashboard');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('user/Dashboard')
        ->has('stats')
        ->has('recentBookings')
        ->has('user')
    );
});

test('authenticated users can access bookings page', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/user/bookings');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('user/Bookings')
        ->has('bookings')
        ->has('counts')
    );
});

test('authenticated users can access profile page', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/user/profile');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('user/Profile')
        ->has('user')
    );
});

test('authenticated users can update profile', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->put('/user/profile', [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'phone' => '+1234567890',
    ]);
    
    $response->assertRedirect(route('user.profile.show'));
    
    $user->refresh();
    expect($user->name)->toBe('Updated Name');
    expect($user->email)->toBe('updated@example.com');
});

test('profile update validates required fields', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->put('/user/profile', [
        'name' => '',
        'email' => '',
    ]);
    
    $response->assertSessionHasErrors(['name', 'email']);
});

test('authenticated users can update password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('old-password'),
    ]);
    
    $response = $this->actingAs($user)->put('/user/password', [
        'current_password' => 'old-password',
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);
    
    $response->assertRedirect(route('user.profile.show'));
    
    $user->refresh();
    expect(password_verify('new-password123', $user->password))->toBeTrue();
});

test('password update validates current password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('old-password'),
    ]);
    
    $response = $this->actingAs($user)->put('/user/password', [
        'current_password' => 'wrong-password',
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);
    
    $response->assertSessionHasErrors(['current_password']);
});

test('password update validates password confirmation', function () {
    $user = User::factory()->create([
        'password' => bcrypt('old-password'),
    ]);
    
    $response = $this->actingAs($user)->put('/user/password', [
        'current_password' => 'old-password',
        'password' => 'new-password123',
        'password_confirmation' => 'different-password',
    ]);
    
    $response->assertSessionHasErrors(['password']);
});
