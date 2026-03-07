<x-admin-layout
    title="Soporte | MediCare"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Soporte',
        ],
    ]"
>

    <div class="mb-4 flex justify-end">
        <x-wire-button href="{{ route('admin.support.create') }}" blue>
            Crear Ticket
        </x-wire-button>
    </div>

    @livewire('admin.datatables.support-table')

</x-admin-layout>
