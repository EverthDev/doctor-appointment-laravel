<x-admin-layout
    title="Roles | MediCare"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Roles',
            'href' => route('admin.roles.index'),
        ],
        [
            'name' => 'Crear',
        ],
    ]"
>
    <x-wire-card title="Registrar Nuevo Rol">

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <x-wire-input
                    label="Nombre del rol"
                    name="name"
                    required
                    :value="old('name')"
                    placeholder="Ej. Administrador, Doctor, Recepcionista"
                />

                {{-- Si luego agregas permisos, aquí irían --}}
            </div>

            <div class="flex justify-end items-center gap-2 mt-6 pt-4 border-t border-gray-200">
                <x-wire-button href="{{ route('admin.roles.index') }}" flat label="Cancelar" />
                <x-wire-button type="submit" blue label="Guardar Rol" />
            </div>

        </form>

    </x-wire-card>
</x-admin-layout>
