<x-admin-layout
    title="Crear Ticket | MediCare"
    :breadcrumbs="[
        ['name'=>'Dashboard','href'=>route('admin.dashboard')],
        ['name'=>'Soporte','href'=>route('admin.support.index')],
        ['name'=>'Crear']
    ]">

    <x-wire-card title="Nuevo Ticket de Soporte">
        <form action="{{ route('admin.support.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <x-wire-input
                    name="title"
                    label="Título del Ticket"
                    required
                />

                <x-wire-textarea
                    name="description"
                    label="Descripción"
                    rows="4"
                    required
                />
            </div>

            <div class="flex justify-end mt-6">
                <x-wire-button type="submit">
                    Crear Ticket
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>
