<div class="space-y-6">
    <!-- Header section -->
    <div class="border-b pb-4 mt-2">
        <h2 class="text-xl font-bold text-gray-800">Buscar disponibilidad</h2>
        <p class="text-sm text-gray-500">Seleccione una fecha y especialidad para ver los horarios disponibles.</p>
    </div>

    <!-- Filters section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
            <input type="date" wire:model.live="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
            <x-wire-native-select wire:model.live="speciality_id" class="w-full placeholder-gray-400">
                <option value="">Seleccione especialidad...</option>
                @foreach($specialities as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </x-wire-native-select>
            @error('speciality_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-end">
            <button wire:click="searchAvailability" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                Buscar disponibilidad
            </button>
        </div>
    </div>

    @if(count($availableDoctors) > 0)
        <!-- Two columns layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8 border-t pt-8">
            
            <!-- LEFT COLUMN: Doctors and Availability -->
            <div class="lg:col-span-2 space-y-6">
                <h3 class="text-lg font-bold text-gray-800">Doctores Disponibles ({{ count($availableDoctors) }})</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    @foreach($availableDoctors as $doctor)
                        <div class="bg-white border rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="h-16 w-16 rounded-full bg-gray-200 flex-shrink-0 overflow-hidden">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($doctor->user->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $doctor->user->name }}" class="h-full w-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-gray-900">{{ $doctor->user->name }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $doctor->speciality->name ?? 'Sin especialidad' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Available Hours -->
                            <div>
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Horarios Disponibles</h5>
                                @if(isset($availableSchedules[$doctor->id]) && count($availableSchedules[$doctor->id]) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($availableSchedules[$doctor->id] as $schedule)
                                            <button 
                                                wire:click="selectTime({{ $doctor->id }}, '{{ $schedule->hour }}')"
                                                class="px-4 py-2 text-sm font-medium rounded-md transition-colors
                                                {{ $selectedDoctorId == $doctor->id && $selectedTime == $schedule->hour 
                                                    ? 'bg-blue-600 text-white ring-2 ring-blue-600 ring-offset-2' 
                                                    : 'bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100' }}">
                                                {{ \Carbon\Carbon::parse($schedule->hour)->format('H:i') }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic">No hay horarios disponibles para esta fecha.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- RIGHT COLUMN: Resumen de cita -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-xl p-6 border shadow-inner sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Resumen de Cita</h3>

                    <div class="space-y-4">
                        @if($selectedDoctor)
                            <div class="bg-white p-3 rounded shadow-sm border text-sm">
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-gray-500 font-medium">Doctor:</span>
                                    <span class="col-span-2 font-semibold">{{ $selectedDoctor->user->name }}</span>
                                    
                                    <span class="text-gray-500 font-medium">Fecha:</span>
                                    <span class="col-span-2 font-semibold">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                                    
                                    <span class="text-gray-500 font-medium">Hora:</span>
                                    <span class="col-span-2 font-semibold text-blue-600">{{ \Carbon\Carbon::parse($selectedTime)->format('H:i') }}</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Paciente <span class="text-red-500">*</span></label>
                                <x-wire-native-select wire:model="selectedPatientId" class="w-full placeholder-gray-400">
                                    <option value="">Seleccione paciente...</option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}">{{ $p->user->name }} ({{ $p->user->id_number }})</option>
                                    @endforeach
                                </x-wire-native-select>
                                @error('selectedPatientId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de consulta (Opcional)</label>
                                <textarea wire:model="reason" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Escriba aquí el motivo de la consulta..."></textarea>
                                @error('reason') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-6">
                                <button wire:click="confirmAppointment" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded shadow-lg transition-colors flex justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Confirmar cita
                                </button>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm">Seleccione un doctor y un horario para continuar.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    @elseif($speciality_id && $date)
        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700 font-medium">
                        No se encontraron doctores disponibles para la fecha y especialidad seleccionadas.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
