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
        // 1. Administrador principal (para pruebas)
        $admin1 = User::firstOrCreate(
            ['email' => 'prueba@gmail.mx'],
            [
                'name' => 'Everth Kantún',
                'password' => bcrypt('12345678'),
                'id_number' => '1234567890',
                'phone' => '9999302912',
                'address' => 'Calle 123, Colonia 2',
            ]
        );
        $admin1->syncRoles(['Administrador']);

        // 2. Otro Administrador extra (haciendo un total de 2)
        $admin2 = User::factory()->create();
        $admin2->syncRoles(['Administrador']);

        // 3. Dos Recepcionistas
        $recepcionistas = User::factory()->count(2)->create();
        foreach ($recepcionistas as $rec) {
            $rec->syncRoles(['Recepcionista']);
        }

        // 4. Diez Doctores
        $doctores = User::factory()->count(10)->create();
        $specialitiesCount = \App\Models\Speciality::count() ?: 15;
        foreach ($doctores as $doc) {
            $doc->syncRoles(['Doctor']);
            \App\Models\Doctor::create([
                'user_id' => $doc->id,
                'speciality_id' => rand(1, $specialitiesCount),
                'medical_license_number' => 'MED-' . rand(10000, 99999) . '-' . $doc->id,
                'biography' => 'Medico especialista con ámplia experiencia.'
            ]);
        }

        // 5. Cinco Pacientes
        $pacientes = User::factory()->count(5)->create();
        $bloodTypesCount = \App\Models\BloodType::count() ?: 8;
        foreach ($pacientes as $pac) {
            $pac->syncRoles(['Paciente']);
            \App\Models\Patient::create([
                'user_id' => $pac->id,
                'blood_type_id' => rand(1, $bloodTypesCount),
                'allergies' => 'Ninguna',
            ]);
        }
    }
}
