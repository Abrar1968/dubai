<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/*
|--------------------------------------------------------------------------
| Hajj Public Routes Tests
|--------------------------------------------------------------------------
*/

test('home page is accessible', function () {
    $response = $this->get('/');
    
    $response->assertStatus(200);
});

test('hajj home page is accessible', function () {
    $response = $this->get('/hajj-umrah');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('hajj&umrah/hajjhome')
    );
});

test('hajj packages page is accessible', function () {
    $response = $this->get('/hajjpackage');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('hajj&umrah/hajjpackage')
    );
});

test('umrah packages page is accessible', function () {
    $response = $this->get('/umrahpackage');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('hajj&umrah/umrahpackage')
    );
});

test('articles page is accessible', function () {
    $response = $this->get('/articles');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('hajj&umrah/articles')
    );
});

test('team page is accessible', function () {
    $response = $this->get('/hajj-umrah/team');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('hajj&umrah/team')
    );
});

test('contact page is accessible', function () {
    $response = $this->get('/contactus');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('hajj&umrah/contactus')
    );
});
