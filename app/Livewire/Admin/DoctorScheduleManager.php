<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorScheduleManager extends Component
{
    public Doctor $doctor;
    
    // Array of selected hours per day: [ day => [hour1, hour2, ...], day2 => [...] ]
    public $schedules = [];
    
    public $daysOfWeek = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo'
    ];

    public $workingHours = [];

    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor;
        
        // Generate hours from 08:00 to 17:00
        for ($i = 8; $i <= 17; $i++) {
            $this->workingHours[] = sprintf('%02d:00:00', $i);
        }

        // Initialize array
        foreach ($this->daysOfWeek as $day => $name) {
            $this->schedules[$day] = [];
        }

        // Load existing schedules
        $existing = DoctorSchedule::where('doctor_id', $this->doctor->id)->get();
        foreach ($existing as $schedule) {
            $this->schedules[$schedule->day][] = $schedule->hour;
        }
    }

    public function saveSchedules()
    {
        DoctorSchedule::where('doctor_id', $this->doctor->id)->delete();

        $inserts = [];
        foreach ($this->schedules as $day => $hours) {
            foreach ($hours as $hour) {
                if ($hour) {
                    $inserts[] = [
                        'doctor_id' => $this->doctor->id,
                        'day' => $day,
                        'hour' => $hour,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DoctorSchedule::insert($inserts);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Horario actualizado',
            'text' => 'Los horarios fueron guardados correctamente.',
        ]);
        
        return redirect()->route('admin.doctors.index');
    }

    public function render()
    {
        return view('livewire.admin.doctor-schedule-manager');
    }
}
