@php
$errorGroups = [
'profesional'=>[
'medical_license_number',
'speciality_id',
'biography'
]
];

$initialTab='datos-personales';

foreach ($errorGroups as $tab=>$fields){
if($errors->hasAny($fields)){
$initialTab=$tab;
break;
}
}
@endphp

<x-admin-layout
    title="Editar Doctor | MediCare"
    :breadcrumbs="[
        ['name'=>'Dashboard','href'=>route('admin.dashboard')],
        ['name'=>'Doctores','href'=>route('admin.doctors.index')],
        ['name'=>'Editar']
        ]">
    <form method="POST"
    action="{{ route('admin.doctors.update',$doctor) }}">
    @csrf
    @method('PUT')
    <x-wire-card>

        <div class="lg:flex justify-between items-center">

            <div class="flex items-center">

                <img
                src="{{ $doctor->user->profile_photo_url }}"
                class="h-20 w-20 rounded-full">
                <div>
                <p class="text-2xl font-bold ml-4">
                {{ $doctor->user->name }}
                </p>
                <p class="text-sm text-gray-500 ml-4">
                Licencia:
                {{ $doctor->medical_license_number ?: 'N/A' }}
                </p>
                </div>
            </div>
            


            <div class="flex items-center gap-4">

                <x-wire-button
                outline
                href="{{ route('admin.doctors.index') }}">
                Volver
                </x-wire-button>

                <x-wire-button 
                    outline 
                    class="border-blue-500 text-blue-500 hover:bg-blue-50 inline-flex items-center"
                    href="{{ route('admin.doctors.schedules', $doctor) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5 mr-1" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Horarios
                </x-wire-button>

                <x-wire-button type="submit">
                Guardar cambios
                </x-wire-button>

            </div>

        </div>

    </x-wire-card>


        <x-wire-card>
<div class="space-y-4">

    {{-- Especialidad --}}
    <div>
        <x-wire-native-select
            name="speciality_id"
            label="Especialidad">

            <option value="">
                Selecciona una especialidad
            </option>

            @foreach ($specialities as $speciality)
                <option
                    value="{{ $speciality->id }}"
                    @selected(
                        old(
                            'speciality_id',
                            $doctor->speciality_id
                        ) == $speciality->id
                    )
                >
                    {{ $speciality->name }}
                </option>
            @endforeach

        </x-wire-native-select>
    </div>

    {{-- Licencia médica --}}
    <div>
        <x-wire-input
            name="medical_license_number"
            label="Número de licencia médica"
            type="number"
            inputmode="numeric"
            pattern="[0-9]*"
            :value="old(
                'medical_license_number',
                $doctor->medical_license_number
            )"
        />
    </div>

    {{-- Biografía --}}
    <div class="lg:col-span-2">
        <x-wire-textarea
            name="biography"
            label="Biografía"
            rows="4"
            :value="old(
                'biography',
                $doctor->biography
            )"
        />
    </div>
</div>
        </x-wire-card>
    </form>
</x-admin-layout>