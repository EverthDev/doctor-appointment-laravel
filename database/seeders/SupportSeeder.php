<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Support;
use App\Models\User;

class SupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first(); // Asignamos el primer usuario encontrado

        if ($user) {
            Support::create([
                'user_id' => $user->id,
                'title' => 'Problema de acceso',
                'description' => 'No puedo acceder a mi cuenta desde mi dispositivo móvil.',
                'status' => 'open',
            ]);

            Support::create([
                'user_id' => $user->id,
                'title' => 'Cambio de especialidad médica',
                'description' => 'Me gustaría que se agregue una nueva especialidad.',
                'status' => 'in_progress',
            ]);

            Support::create([
                'user_id' => $user->id,
                'title' => 'Error en cita',
                'description' => 'La cita agendada para ayer aparece como pendiente.',
                'status' => 'closed',
            ]);
        }
    }
}
