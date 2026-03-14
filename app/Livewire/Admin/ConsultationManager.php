<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Patient;

class ConsultationManager extends Component
{
    public Appointment $appointment;
    public Patient $patient;

    // Tabs
    public $activeTab = 'consulta'; // consulta | receta

    // Consulta fields
    public $diagnosis = '';
    public $treatment = '';
    public $notes = '';

    // Receta fields (dynamic)
    public $medications = [];

    // Modals
    public $showHistoryModal = false;
    public $showPreviousConsultationsModal = false;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment->load(['patient.user', 'doctor.user']);
        $this->patient = $this->appointment->patient;

        // Initialize with one empty medication row
        $this->addMedication();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Medication methods
    public function addMedication()
    {
        $this->medications[] = [
            'name' => '',
            'dose' => '',
            'frequency' => ''
        ];
    }

    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications); // Reindex
    }

    public function saveConsultation()
    {
        $this->validate([
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'notes' => 'nullable|string',
            'medications.*.name' => 'required_with:medications.*.dose|string',
        ]);

        // Automatically update to Completado
        $this->appointment->update([
            'status' => 2 // 2 = Completado
            // In a real system, you'd also save the diagnosis, treatment, and medications to their respective tables.
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Consulta Completada',
            'text' => 'Los datos de la consulta han sido guardados y la cita fue marcada como Completada.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        // Load previous consultations for the modal
        $previousConsultations = Appointment::where('patient_id', $this->patient->id)
            ->where('id', '!=', $this->appointment->id)
            ->where('status', 2)
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.admin.consultation-manager', [
            'previousConsultations' => $previousConsultations
        ])->layout('layouts.admin', [
            'title' => 'Consulta | MediCare',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
                ['name' => 'Citas', 'href' => route('admin.appointments.index')],
                ['name' => 'Consulta']
            ]
        ]);
    }
}
