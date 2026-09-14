<?php

use App\Models\User;

it('permite registro de novo usuário', function () {
    $response = $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect('/');
    expect(User::where('email', 'test@example.com')->exists())->toBeTrue();
});

it('permite login com credenciais válidas', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('password123'),
    ]);

    $this->post(route('login'), [
        'email' => 'user@example.com',
        'password' => 'password123',
    ])->assertRedirect('/');

    $this->assertAuthenticated();
});
