<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Speciality;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentCreator extends Component
{
    public $date;
    public $speciality_id;
    
    // Available doctors and schedules for the selected date/speciality
    public $availableDoctors = [];
    public $availableSchedules = [];

    // Selections
    public $selectedDoctorId;
    public $selectedTime;
    public $selectedPatientId;
    public $reason;

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function searchAvailability()
    {
        $this->validate([
            'date' => 'required|date|after_or_equal:today',
            'speciality_id' => 'required|exists:specialities,id',
        ]);

        $dayOfWeek = Carbon::parse($this->date)->dayOfWeekIso; // 1 (Mon) - 7 (Sun)

        // Find doctors with the selected speciality
        $doctors = Doctor::with(['user', 'speciality'])
            ->where('speciality_id', $this->speciality_id)
            ->whereHas('schedules', function($query) use ($dayOfWeek) {
                $query->where('day', $dayOfWeek);
            })
            ->get();

        $this->availableDoctors = $doctors;
        $this->availableSchedules = [];
        $this->selectedDoctorId = null;
        $this->selectedTime = null;

        // Fetch schedules for these doctors
        foreach ($doctors as $doctor) {
            $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
                ->where('day', $dayOfWeek)
                ->orderBy('hour')
                ->get();
            
            // Filter out hours already booked on this exact date
            $bookedHours = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $this->date)
                ->whereIn('status', [1]) // Only Programado
                ->pluck('start_time')
                ->map(fn($time) => Carbon::parse($time)->format('H:i:s'))
                ->toArray();

            $freeSchedules = $schedules->filter(function($schedule) use ($bookedHours) {
                return !in_array($schedule->hour, $bookedHours);
            });

            $this->availableSchedules[$doctor->id] = $freeSchedules;
        }
    }

    public function selectTime($doctorId, $time)
    {
        $this->selectedDoctorId = $doctorId;
        $this->selectedTime = $time;
    }

    public function confirmAppointment()
    {
        $this->validate([
            'selectedDoctorId' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'selectedTime' => 'required',
            'selectedPatientId' => 'required|exists:patients,id',
            'reason' => 'nullable|string',
        ]);

        $start_time = Carbon::parse($this->selectedTime)->format('H:i:s');
        $end_time = Carbon::parse($this->selectedTime)->addMinutes(15)->format('H:i:s'); // Default duration 15m assumed

        // Double check it wasn't booked in the meantime
        $exists = Appointment::where('doctor_id', $this->selectedDoctorId)
            ->where('date', $this->date)
            ->where('start_time', $start_time)
            ->whereIn('status', [1])
            ->exists();

        if ($exists) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'El horario seleccionado ya no está disponible.',
            ]);
            $this->searchAvailability();
            return;
        }

        Appointment::create([
            'patient_id' => $this->selectedPatientId,
            'doctor_id' => $this->selectedDoctorId,
            'date' => $this->date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'duration' => 15,
            'reason' => $this->reason,
            'status' => 1, // Programado
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita Confirmada',
            'text' => 'La cita se ha programado correctamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.appointment-creator', [
            'specialities' => Speciality::all(),
            'patients' => Patient::with('user')->get(),
            'selectedDoctor' => $this->selectedDoctorId ? Doctor::with(['user', 'speciality'])->find($this->selectedDoctorId) : null,
        ]);
    }
}
