<x-admin-layout
    title="Editar Cita | MediCare"
    :breadcrumbs="[
        ['name'=>'Dashboard','href'=>route('admin.dashboard')],
        ['name'=>'Citas','href'=>route('admin.appointments.index')],
        ['name'=>'Editar']
    ]">
    <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}">
        @csrf
        @method('PUT')
        
        <x-wire-card>
            <div class="lg:flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Cita #{{ $appointment->id }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Paciente: <span class="font-semibold">{{ $appointment->patient->user->name }}</span> | 
                        Doctor: <span class="font-semibold">{{ $appointment->doctor->user->name }}</span>
                    </p>
                </div>
                
                <div class="space-x-2 mt-4 lg:mt-0 flex">
                    <x-wire-button outline href="{{ route('admin.appointments.index') }}">
                        Volver
                    </x-wire-button>

                    <x-wire-button type="submit" class="bg-blue-600">
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Aviso:</strong> Por razones de integridad médica, no se permite modificar la fecha, hora o doctor directamente. Si requiere un cambio mayor, por favor cancele la cita y cree una nueva.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Info Section -->
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-md border">
                        <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Detalles Originales</h3>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <span class="text-gray-500">Fecha:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</span>
                            
                            <span class="text-gray-500">Hora:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</span>
                            
                            <span class="text-gray-500">Estado Actual:</span>
                            <span class="font-medium">
                                @switch($appointment->status)
                                    @case(1) <span class="text-blue-600">Programado</span> @break
                                    @case(2) <span class="text-green-600">Completado</span> @break
                                    @case(3) <span class="text-yellow-600">Reagendado</span> @break
                                    @case(4) <span class="text-red-600">Cancelado</span> @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Editable Fields -->
                <div class="space-y-4">
                    <div>
                        <x-wire-native-select name="status" label="Cambiar Estado">
                            <option value="1" @selected(old('status', $appointment->status) == 1)>Programado</option>
                            <option value="3" @selected(old('status', $appointment->status) == 3)>Reagendado</option>
                            <option value="4" @selected(old('status', $appointment->status) == 4)>Cancelado</option>
                        </x-wire-native-select>
                        <p class="text-xs text-gray-500 mt-1">El estado "Completado" se asigna automáticamente al realizar la consulta.</p>
                    </div>

                    <div>
                        <x-wire-textarea 
                            name="reason" 
                            label="Motivo de la cita" 
                            rows="4" 
                            :value="old('reason', $appointment->reason)"/>
                    </div>
                </div>

            </div>
        </x-wire-card>
    </form>
</x-admin-layout>
