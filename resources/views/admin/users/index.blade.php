<x-admin-layout title="Usuarios | Medicare" :breadcrumbs="[
    ['name'=> 'Dashboard', 'href'=> route('admin.dashboard')],
    ['name'=> 'Usuarios'],
]">
    <x-slot name="action">
        <x-wire-button href="{{ route('admin.users.create') }}" blue>
            <i class="fa-solid fa-plus"></i>
            <span class="ml-1">Nuevo</span>
        </x-wire-button>
    </x-slot>
    @livewire('admin.datatables.user-table')
</x-admin-layout>