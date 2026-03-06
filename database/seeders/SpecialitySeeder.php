<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Speciality;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $specialities = [
        'Cardiología',
        'Dermatología',
        'Pediatría',
        'Neurología',
        'Medicina General',
        'Ginecología',
        'Traumatología',
        'Oftalmología',
        'Otorrinolaringología',
        'Psiquiatría',
        'Endocrinología',
        'Gastroenterología',
        'Neumología',
        'Urología',
        'Oncología'
    ];

        foreach ($specialities as $name) {
            Speciality::firstOrCreate([
                'name' => $name
            ]);
        }
    }
}
