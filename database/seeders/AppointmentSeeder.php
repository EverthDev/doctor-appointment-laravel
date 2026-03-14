<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        if ($doctors->isEmpty() || $patients->isEmpty()) {
            return;
        }

        $statuses = [1, 2, 3, 4]; // 1: Programado, 2: Completado, 3: Reagendado, 4: Cancelado

        for ($i = 0; $i < 50; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();
            
            // Random date within the next 30 days
            $date = Carbon::now()->addDays(rand(1, 30));
            // Random hour between 08:00 and 16:00
            $startHour = rand(8, 16);
            $startTime = Carbon::createFromTime($startHour, 0, 0);
            $endTime = $startTime->copy()->addMinutes(15);

            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'date' => $date->format('Y-m-d'),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'duration' => 15,
                'reason' => 'Consulta general de rutina ' . rand(1, 100),
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
