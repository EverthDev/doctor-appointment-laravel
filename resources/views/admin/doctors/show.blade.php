<x-admin-layout
title="Detalle Doctor | MediCare"
:breadcrumbs="[
['name'=>'Dashboard','href'=>route('admin.dashboard')],
['name'=>'Doctores','href'=>route('admin.doctors.index')],
['name'=>'Detalle']
]">

<x-wire-card>

<h2 class="text-xl font-bold">
{{ $doctor->user->name }}
</h2>

<p>
Especialidad:
{{ $doctor->speciality->name ?? 'N/A' }}
</p>

<p>
Cédula:
{{ $doctor->medical_license_number ?? 'N/A' }}
</p>

<p>
Biografía:
{{ $doctor->biography ?? 'N/A' }}
</p>

</x-wire-card>

</x-admin-layout>