<?php

use App\Models\User;
use App\Models\AdminSection;
use App\Enums\UserRole;

/*
|--------------------------------------------------------------------------
| Admin Panel Access Tests
|--------------------------------------------------------------------------
| Note: These tests verify basic access control for admin panel routes.
| The admin panel uses middleware to check role and section assignments.
*/

test('guests are redirected from admin panel', function () {
    $response = $this->get('/admin');
    
    $response->assertRedirect();
});

test('regular users cannot access admin panel', function () {
    $user = User::factory()->create([
        'role' => UserRole::USER,
    ]);
    
    $response = $this->actingAs($user)->get('/admin');
    
    $response->assertStatus(403);
});

test('admins without section assignment get restricted', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);
    
    // No section assignment - should redirect or get 403
    $response = $this->actingAs($admin)->get('/admin/hajj/packages');
    
    expect($response->status())->toBeIn([302, 403]);
});

test('admin role is required to access admin routes', function () {
    $user = User::factory()->create([
        'role' => UserRole::USER,
    ]);
    
    $response = $this->actingAs($user)->get('/admin/hajj/packages');
    
    $response->assertStatus(403);
});
