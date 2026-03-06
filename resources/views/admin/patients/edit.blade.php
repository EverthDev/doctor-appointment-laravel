{{-- lógica de PHP para manejar errores y controlar la pestaña activa --}}
@php
    // definimos qué campos pertenecen a cada pestaña
    $errorGroups = [
        'antecedentes' => ['allergies', 'chronic_conditions', 'surgical_history', 'family_history'],
        'información-general' => ['blood_type_id', 'observations'],
        'contacto-emergencia' => ['emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'],
    ];

    // Pestaña por defecto
    $initialTab = 'datos-personales';

    // Detectar automáticamente la pestaña con errores
    foreach ($errorGroups as $tabName => $fields) {
        if ($errors->hasAny($fields)) {
            $initialTab = $tabName;
            break;
        }
    }
    
    // Variables de error para cada pestaña
    $antecedentesError = $errors->hasAny($errorGroups['antecedentes']);
    $informacionGeneralError = $errors->hasAny($errorGroups['información-general']);
    $contactoEmergenciaError = $errors->hasAny($errorGroups['contacto-emergencia']);
@endphp

<x-admin-layout
    title="Pacientes | MediCare"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
        ['name' => 'Editar'],
    ]"
>
    <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')
        
        {{-- Encabezado con foto y acciones --}}
        <x-wire-card class="ml-8">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center">
                    <img 
                        src="{{ $patient->user->profile_photo_url }}" 
                        alt="{{ $patient->user->name }}"
                        class="h-20 w-20 rounded-full object-cover object-center"
                    >
                    <div>
                        <p class="text-2xl font-bold text-gray-900 ml-4">
                            {{ $patient->user->name }}
                        </p>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.patients.index') }}">
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check"></i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        {{-- Tabs de navegación --}}
        <x-wire-card>
            <x-tabs :active="$initialTab">
                {{-- Header  de las pestañas --}}
                <x-slot name="header">
                    {{-- Tab 1: Datos personales --}}
                    <li class="me-2">
                        <a href="#" 
                           x-on:click.prevent="tab = 'datos-personales'"
                           :class="{
                               'text-blue-600 border-blue-600 active': tab === 'datos-personales',
                               'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== 'datos-personales'
                           }"
                           class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200"
                           :aria-current="tab === 'datos-personales' ? 'page' : undefined"
                        >
                            <i class="fa-solid fa-user me-2"></i>    
                            Datos personales
                        </a>
                    </li>

                    {{-- Tab 2: Antecedentes --}}
                    <x-tabs-link tab="antecedentes" :error="$antecedentesError">
                        <i class="fa-solid fa-file-lines me-2"></i>    
                        Antecedentes
                    </x-tabs-link>

                    {{-- Tab 3: Información general --}}
                    <x-tabs-link tab="información-general" :error="$informacionGeneralError">
                        <i class="fa-solid fa-info me-2"></i>    
                        Información general
                    </x-tabs-link>

                    {{-- Tab 4: Contacto de emergencia --}}
                    <x-tabs-link tab="contacto-emergencia" :error="$contactoEmergenciaError">
                        <i class="fa-solid fa-heart me-2"></i>    
                        Contacto de emergencia
                    </x-tabs-link>
                </x-slot>

                {{-- Contenido de los tabs --}}
                
                {{-- Tab 1: Datos Personales--}}
                <div x-show="tab === 'datos-personales'" style="display: none" x-cloak>
                    {{-- Alert de edición de usuario --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-user-gear text-blue-500 text-xl mt-5"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-blue-800 font-bold">Edición de usuario</h3>
                                    <div class="mt-1 text-sm text-blue-600">
                                        <p class="text-sm text-blue-700 mt-1">
                                            La información de acceso (nombre, email y contraseña) debe gestionarse desde la cuenta de usuario asociada.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.users.edit', $patient->user) }}"
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200"
                                >
                                    Editar usuario
                                    <i class="fa-solid fa-arrow-up-right-from-square ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Grid de datos personales (solo lectura) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-500 font-semibold text-sm">Teléfono</label>
                            <p class="text-gray-900">{{ $patient->user->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 font-semibold text-sm">Email</label>
                            <p class="text-gray-900">{{ $patient->user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-gray-500 font-semibold text-sm">Dirección</label>
                            <p class="text-gray-900">{{ $patient->user->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tab 2: Antecedentes --}}
                <x-tab-content tab="antecedentes">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <x-wire-textarea
                            name="allergies"
                            label="Alergias"
                            :value="old('allergies', $patient->allergies)"
                            rows="4" />

                        <x-wire-textarea
                            name="chronic_conditions"
                            label="Enfermedades Crónicas"
                            :value="old('chronic_conditions', $patient->chronic_conditions)"
                            rows="4" />

                        <x-wire-textarea
                            name="surgical_history"
                            label="Antecedentes Quirúrgicos"
                            :value="old('surgical_history', $patient->surgical_history)"
                            rows="4" />

                        <x-wire-textarea
                            name="family_history"
                            label="Antecedentes Familiares"
                            :value="old('family_history', $patient->family_history)"
                            rows="4" />
                    </div>
                </x-tab-content>

                {{-- Tab 3: Información General --}}
                <x-tab-content tab="información-general">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <x-wire-native-select name="blood_type_id" label="Tipo de Sangre">
                                <option value="">Selecciona un tipo de sangre</option>
                                @foreach ($bloodTypes as $bloodType)
                                    <option value="{{ $bloodType->id }}" @selected(old('blood_type_id', $patient->blood_type_id) == $bloodType->id)>
                                        {{ $bloodType->type }}
                                    </option>
                                @endforeach
                            </x-wire-native-select>
                        </div>

                        <div class="lg:col-span-2">
                            <x-wire-textarea
                                name="observations"
                                label="Observaciones"
                                :value="old('observations', $patient->observations)"
                                rows="4" />
                        </div>
                    </div>
                </x-tab-content>

                {{-- Tab 4: Contacto de Emergencia --}}
                <x-tab-content tab="contacto-emergencia">
                    <div class="space-y-4">
                        <x-wire-input
                            name="emergency_contact_name"
                            label="Nombre de contacto"
                            :value="old('emergency_contact_name', $patient->emergency_contact_name)" />

                        <x-wire-phone
                            name="emergency_contact_phone"
                            mask="(###) ###-####"
                            placeholder="(999) 999-9999"
                            label="Teléfono de contacto"
                            :value="old('emergency_contact_phone', $patient->emergency_contact_phone)" />

                        <x-wire-input
                            name="emergency_contact_relationship"
                            placeholder="Parentesco"
                            label="Relación con el contacto"
                            :value="old('emergency_contact_relationship', $patient->emergency_contact_relationship)" />
                    </div>
                </x-tab-content>
            </x-tabs>
        </x-wire-card>
    </form>
</x-admin-layout>