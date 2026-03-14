<x-admin-layout
    title="Citas | MediCare"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Citas',
        ],
    ]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.appointments.create') }}" blue>
            <i class="fa-solid fa-plus"></i>
            <span class="ml-1">Nuevo</span>
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.appointment-table')

</x-admin-layout>
