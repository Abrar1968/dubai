<?php

/*
|--------------------------------------------------------------------------
| Contact Form Tests
|--------------------------------------------------------------------------
*/

test('contact form can be submitted', function () {
    $response = $this->post('/contactus', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'subject' => 'General Inquiry',
        'message' => 'This is a test message with at least 10 characters.',
    ]);
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
});

test('contact form validates required fields', function () {
    $response = $this->post('/contactus', [
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => '',
    ]);
    
    $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
});

test('contact form validates email format', function () {
    $response = $this->post('/contactus', [
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'subject' => 'General Inquiry',
        'message' => 'This is a test message.',
    ]);
    
    $response->assertSessionHasErrors(['email']);
});

test('contact form validates max message length', function () {
    $response = $this->post('/contactus', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'General Inquiry',
        'message' => str_repeat('a', 5001), // Exceeds 5000 max
    ]);
    
    $response->assertSessionHasErrors(['message']);
});
