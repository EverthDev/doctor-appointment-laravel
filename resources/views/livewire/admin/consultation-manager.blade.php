<div class="space-y-6">
    <!-- Header section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-full bg-blue-100 flex-shrink-0 overflow-hidden border-2 border-blue-200">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($patient->user->name) }}&color=1D4ED8&background=DBEAFE" alt="{{ $patient->user->name }}" class="h-full w-full object-cover">
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $patient->user->name }}</h2>
                <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-gray-600">
                    <span class="flex items-center"><i class="fa-solid fa-id-card mr-1 text-gray-400"></i> DNI: {{ $patient->user->id_number }}</span>
                    <span class="flex items-center"><i class="fa-solid fa-droplet mr-1 text-red-400"></i> Tipo de Sangre: {{ $patient->bloodType->name ?? 'No registrado' }}</span>
                </div>
            </div>
        </div>
        
        <div class="flex gap-2 w-full md:w-auto">
            <button wire:click="$set('showHistoryModal', true)" class="flex-1 md:flex-none bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium py-2 px-4 rounded border border-indigo-200 transition-colors flex justify-center items-center">
                <i class="fa-solid fa-book-medical mr-2"></i> Ver historia
            </button>
            <button wire:click="$set('showPreviousConsultationsModal', true)" class="flex-1 md:flex-none bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium py-2 px-4 rounded border border-purple-200 transition-colors flex justify-center items-center">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Consultas anteriores
            </button>
        </div>
    </div>

    <!-- Appointment Info -->
    <div class="bg-gray-50 rounded-lg p-4 border flex gap-6 text-sm">
        <div><span class="font-semibold text-gray-700">Doctor:</span> {{ $appointment->doctor->user->name }}</div>
        <div><span class="font-semibold text-gray-700">Fecha:</span> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</div>
        <div><span class="font-semibold text-gray-700">Horario:</span> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</div>
        @if($appointment->reason)
            <div class="col-span-full"><span class="font-semibold text-gray-700">Motivo de consulta:</span> {{ $appointment->reason }}</div>
        @endif
    </div>

    <!-- TABS -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="border-b border-gray-200 flex">
            <button wire:click="setTab('consulta')" class="flex-1 py-4 px-6 text-center focus:outline-none transition-colors font-medium border-b-2 {{ $activeTab === 'consulta' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="fa-solid fa-stethoscope mr-2"></i> Consulta
            </button>
            <button wire:click="setTab('receta')" class="flex-1 py-4 px-6 text-center focus:outline-none transition-colors font-medium border-b-2 {{ $activeTab === 'receta' ? 'border-green-500 text-green-600 bg-green-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="fa-solid fa-prescription-bottle-medical mr-2"></i> Receta
            </button>
        </div>

        <form wire:submit.prevent="saveConsultation" class="p-6">
            
            <!-- TABS CONTENT -->
            <div class="{{ $activeTab === 'consulta' ? 'block' : 'hidden' }} space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnóstico <span class="text-red-500">*</span></label>
                    <textarea wire:model="diagnosis" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Escriba el diagnóstico detallado..."></textarea>
                    @error('diagnosis') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tratamiento <span class="text-red-500">*</span></label>
                    <textarea wire:model="treatment" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Escriba el plan de tratamiento..."></textarea>
                    @error('treatment') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notas u observaciones</label>
                    <textarea wire:model="notes" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Notas internas adicionales (opcional)..."></textarea>
                    @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="{{ $activeTab === 'receta' ? 'block' : 'hidden' }} space-y-6">
                
                <div class="overflow-hidden border rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-5/12">Medicamento</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">Dosis</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">Frecuencia / Duración</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($medications as $index => $medication)
                            <tr wire:key="medication-{{ $index }}">
                                <td class="px-4 py-2">
                                    <input type="text" wire:model="medications.{{ $index }}.name" class="w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500 placeholder-gray-400" placeholder="Ej. Paracetamol 500mg">
                                    @error('medications.'.$index.'.name') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-4 py-2">
                                    <input type="text" wire:model="medications.{{ $index }}.dose" class="w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500 placeholder-gray-400" placeholder="Ej. 1 tableta">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="text" wire:model="medications.{{ $index }}.frequency" class="w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500 placeholder-gray-400" placeholder="Ej. Cada 8 hrs por 3 días">
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button type="button" wire:click="removeMedication({{ $index }})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-full transition-colors" title="Eliminar medicamento">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if(count($medications) === 0)
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500 italic">No hay medicamentos en la receta.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div>
                    <button type="button" wire:click="addMedication" class="inline-flex items-center px-4 py-2 border border-green-500 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fa-solid fa-plus mr-2"></i> Añadir medicamento
                    </button>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded shadow flex items-center transition-colors">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar Consulta y Completar
                </button>
            </div>
        </form>
    </div>

    <!-- MODAL: MEDICAL HISTORY -->
    <x-dialog-modal wire:model="showHistoryModal">
        <x-slot name="title">
            <h3 class="text-lg font-bold">Historia Médica: {{ $patient->user->name }}</h3>
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-1 border rounded p-4 bg-gray-50">
                    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-2">Datos Generales</h4>
                    <p class="text-sm"><span class="text-gray-500">Tipo de Sangre:</span> <span class="font-medium">{{ $patient->bloodType->name ?? 'No registrado' }}</span></p>
                    <p class="text-sm mt-2"><span class="text-gray-500">Enfermedades crónicas:</span> <br> <span class="font-medium">{{ $patient->chronic_diseases ?? 'Ninguna registrada' }}</span></p>
                </div>
                
                <div class="col-span-1 border rounded p-4 bg-red-50">
                    <h4 class="font-semibold text-red-700 border-b border-red-200 pb-2 mb-2">Alergias</h4>
                    <p class="text-sm font-medium text-red-800">{{ $patient->allergies ?? 'Ninguna registrada' }}</p>
                </div>

                <div class="col-span-1 md:col-span-2 border rounded p-4 bg-gray-50 mt-2">
                    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-2">Historial Quirúrgico</h4>
                    <p class="text-sm font-medium">{{ $patient->surgical_history ?? 'Ninguno registrado' }}</p>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ route('admin.patients.edit', $patient) }}" target="_blank" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Editar historia clínica
                </a>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showHistoryModal', false)">Cerrar</x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- MODAL: PREVIOUS CONSULTATIONS -->
    <x-dialog-modal wire:model="showPreviousConsultationsModal" maxWidth="4xl">
        <x-slot name="title">
            <h3 class="text-lg font-bold">Consultas Anteriores</h3>
        </x-slot>

        <x-slot name="content">
            @if(count($previousConsultations) > 0)
                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                    @foreach($previousConsultations as $prev)
                        <div class="border rounded-lg p-4 bg-white shadow-sm border-l-4 border-l-blue-500">
                            <div class="flex justify-between items-start mb-3 border-b pb-2">
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($prev->date)->format('d \d\e F, Y') }}</h4>
                                    <p class="text-xs text-gray-500"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($prev->start_time)->format('H:i') }} | <i class="fa-solid fa-user-doctor"></i> Dr. {{ $prev->doctor->user->name }}</p>
                                </div>
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">Cita #{{ $prev->id }}</span>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-3 text-sm">
                                <div>
                                    <span class="font-semibold text-gray-700 block">Motivo:</span>
                                    <p class="text-gray-600">{{ $prev->reason ?: 'No especificado' }}</p>
                                </div>
                                <!-- In a real app we would load diagnosis/treatment from related tables here. 
                                     As no explicit tables were requested for those in the schema, we display a placeholder message. -->
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="text-gray-500 italic"><i class="fa-solid fa-lock text-xs"></i> Los detalles clínicos de esta consulta están archivados.</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300"></i>
                    <p>No se encontraron consultas previas para este paciente.</p>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showPreviousConsultationsModal', false)">Cerrar</x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>
