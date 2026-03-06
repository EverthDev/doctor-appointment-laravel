<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cannot create a user with invalid data', function () {

    // 1) Usuario autenticado
    $authUser = User::factory()->create();
    $this->actingAs($authUser);

    // 2) Intentar crear usuario SIN email
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Usuario inválido',
        'email' => '', // inválido
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_number' => 'ABC98765',
        'phone' => '9999999999',
        'address' => 'Dirección inválida',
    ]);

    // 3) Validación falla → redirect
    $response->assertStatus(302);

    // 4) El usuario NO debe existir
    $this->assertDatabaseMissing('users', [
        'id_number' => 'ABC98765',
    ]);
});
