<x-admin-layout
    title="Crear Cita | MediCare"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Citas',
            'href' => route('admin.appointments.index'),
        ],
        [
            'name' => 'Crear',
        ],
    ]"
>

    <div class="bg-white rounded-lg shadow-lg p-6">
        @livewire('admin.appointment-creator')
    </div>

</x-admin-layout>
