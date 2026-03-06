<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can update a user', function () {

    // Seed de roles
    $this->seed(RoleSeeder::class);

    // Usuario autenticado
    $authUser = User::factory()->create();
    $this->actingAs($authUser);

    // Rol existente
    $role = Role::where('name', 'Doctor')->firstOrFail();

    // Usuario a actualizar (con phone válido)
    $user = User::factory()->create([
        'phone' => '9999999999',
    ]);
    $user->assignRole($role);

    // Update
    $response = $this->put(route('admin.users.update', $user), [
        'name' => 'Usuario Actualizado',
        'email' => 'actualizado@example.com',
        'id_number' => $user->id_number,
        'phone' => '8888888888',
        'address' => 'Dirección actualizada',
        'role_id' => $role->id,
    ]);

    // Redirect esperado
    $response->assertStatus(302);

    // Verificar update en BD
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Usuario Actualizado',
        'email' => 'actualizado@example.com',
    ]);

    // Verificar rol
    expect($user->fresh()->hasRole('Doctor'))->toBeTrue();
});
