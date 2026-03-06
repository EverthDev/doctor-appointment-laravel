<x-admin-layout
title="Registrar Doctor | MediCare"
:breadcrumbs="[
['name'=>'Dashboard','href'=>route('admin.dashboard')],
['name'=>'Doctores','href'=>route('admin.doctors.index')],
['name'=>'Crear']
]">

<x-wire-card title="Perfil Profesional">

<form action="{{ route('admin.doctors.store') }}" method="POST">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

<x-wire-native-select name="user_id" label="Usuario Doctor">
<option value="">Seleccionar usuario</option>

@foreach($users as $user)
<option value="{{ $user->id }}">
{{ $user->name }}
</option>
@endforeach
</x-wire-native-select>

<x-wire-input
name="medical_license_number"
label="Cédula Profesional"
required
/>

</div>

<div class="mt-4">

<x-wire-native-select
name="speciality_id"
label="Especialidad">

<option value="">Seleccionar</option>

@foreach($specialities as $speciality)
<option value="{{ $speciality->id }}">
{{ $speciality->name }}
</option>
@endforeach

</x-wire-native-select>

</div>

<div class="mt-4">

<x-wire-textarea
name="biography"
label="Biografía"
rows="4"
/>

</div>

<div class="flex justify-end mt-6">
<x-wire-button type="submit">
Guardar Doctor
</x-wire-button>
</div>

</form>

</x-wire-card>

</x-admin-layout>