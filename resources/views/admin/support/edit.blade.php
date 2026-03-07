<x-admin-layout
    title="Editar Ticket | MediCare"
    :breadcrumbs="[
        ['name'=>'Dashboard','href'=>route('admin.dashboard')],
        ['name'=>'Soporte','href'=>route('admin.support.index')],
        ['name'=>'Editar']
    ]">
    <form method="POST" action="{{ route('admin.support.update', $support) }}">
        @csrf
        @method('PUT')
        
        <x-wire-card>
            <div class="lg:flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold">Ticket #{{ $support->id }}</h2>
                    <p class="text-sm text-gray-500">Por: {{ $support->user->name }}</p>
                </div>
                <div class="space-x-2 mt-4 lg:mt-0">
                    <x-wire-button outline href="{{ route('admin.support.index') }}">
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit">
                        Actualizar Ticket
                    </x-wire-button>
                </div>
            </div>

            <div class="space-y-4">
                <x-wire-input
                    name="title"
                    label="Título del Ticket"
                    :value="old('title', $support->title)"
                    required
                />

                <x-wire-textarea
                    name="description"
                    label="Descripción"
                    rows="4"
                    :value="old('description', $support->description)"
                    required
                />

                <x-wire-native-select name="status" label="Estado">
                    <option value="open" @selected(old('status', $support->status) == 'open')>Abierto (open)</option>
                    <option value="in_progress" @selected(old('status', $support->status) == 'in_progress')>En Progreso (in_progress)</option>
                    <option value="closed" @selected(old('status', $support->status) == 'closed')>Cerrado (closed)</option>
                </x-wire-native-select>
            </div>
        </x-wire-card>
    </form>
</x-admin-layout>
