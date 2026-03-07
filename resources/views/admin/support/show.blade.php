<x-admin-layout
    title="Detalle Ticket | MediCare"
    :breadcrumbs="[
        ['name'=>'Dashboard','href'=>route('admin.dashboard')],
        ['name'=>'Soporte','href'=>route('admin.support.index')],
        ['name'=>'Detalle']
    ]">

    <x-wire-card>
        <div class="mb-4">
            <h2 class="text-xl font-bold">{{ $support->title }}</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                {{ $support->status == 'open' ? 'bg-green-100 text-green-800' : ($support->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                {{ $support->status == 'open' ? 'open' : ($support->status == 'in_progress' ? 'in_progress' : 'closed') }}
            </span>
        </div>

        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-500 font-bold">Descripción:</p>
                <p class="text-gray-900 mt-1">{{ $support->description }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 font-bold">Usuario:</p>
                    <p class="text-gray-900">{{ $support->user->name }} ({{ $support->user->email }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-bold">Fecha de creación:</p>
                    <p class="text-gray-900">{{ $support->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <x-wire-button outline href="{{ route('admin.support.index') }}">
                Volver
            </x-wire-button>

            <form action="{{ route('admin.support.destroy', $support) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este ticket?');">
                @csrf
                @method('DELETE')
                <x-wire-button type="submit" red>
                    Eliminar
                </x-wire-button>
            </form>
        </div>
    </x-wire-card>

</x-admin-layout>
