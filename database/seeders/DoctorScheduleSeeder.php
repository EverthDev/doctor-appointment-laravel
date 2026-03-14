<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Assign schedules for Monday to Friday (1 to 5)
            for ($day = 1; $day <= 5; $day++) {
                // Working hours: 09:00, 10:00, 11:00, 14:00, 15:00, 16:00
                $workingHours = [
                    '09:00:00',
                    '10:00:00',
                    '11:00:00',
                    '14:00:00',
                    '15:00:00',
                    '16:00:00',
                ];

                foreach ($workingHours as $hour) {
                    DoctorSchedule::create([
                        'doctor_id' => $doctor->id,
                        'day' => $day,
                        'hour' => $hour,
                    ]);
                }
            }
        }
    }
}
