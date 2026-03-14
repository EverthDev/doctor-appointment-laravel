<x-admin-layout
    title="Gestor de Horarios | MediCare"
    :breadcrumbs="[
        ['name'=>'Dashboard','href'=>route('admin.dashboard')],
        ['name'=>'Doctores','href'=>route('admin.doctors.index')],
        ['name'=>'Gestor de Horarios']
    ]">

    <div class="bg-white rounded-lg shadow-lg p-6">
        @livewire('admin.doctor-schedule-manager', ['doctor' => $doctor])
    </div>

</x-admin-layout>
