<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                //crear usuario de prueba cada que se ejecuten migraciones
        //php artisan migrate:fresh --seed
        $user = User::firstOrCreate(
            [
                'email' => 'prueba@gmail.mx', // SOLO criterio único
            ],
            [
                'name' => 'Everth Kantún',
                'password' => bcrypt('12345678'),
                'id_number' => '1234567890',
                'phone' => '9999302912',
                'address' => 'Calle 123, Colonia 2',
            ]
        );
        $user->syncRoles(['Doctor']);
    }
}
