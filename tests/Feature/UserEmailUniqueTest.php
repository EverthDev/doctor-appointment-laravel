<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cannot create a user with a duplicate email', function () {

    // 1) Creamos un usuario existente
    $existingUser = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    // 2) Autenticamos como ese usuario (admin o cualquiera autenticado)
    $this->actingAs($existingUser);

    // 3) Intentamos crear otro usuario con el mismo email
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Otro Usuario',
        'email' => 'test@example.com', // email duplicado
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_number' => 'ABC12345',
        'phone' => '9999999999',
        'address' => 'Calle falsa 123',
        'role_id' => 1,
    ]);

    // 4) Esperamos error de validación
    $response->assertSessionHasErrors('email');

    // 5) Verificamos que solo existe un usuario con ese email
    $this->assertEquals(
        1,
        User::where('email', 'test@example.com')->count()
    );
});
