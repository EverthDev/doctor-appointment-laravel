<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Speciality;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * LISTADO
     */
    public function index()
    {
        return view('admin.doctors.index');
    }

    /**
     * FORM CREAR
     */
    public function create()
    {
        $users = User::role('Doctor')->doesntHave('doctor')->get();
        $specialities = Speciality::all();

        return view('admin.doctors.create', compact(
            'users',
            'specialities'
        ));
    }

    /**
     * GUARDAR
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:doctors,user_id',
            'medical_license_number' => 'required|string|max:50',
            'speciality_id' => 'nullable|exists:specialities,id',
            'biography' => 'nullable|string',
        ]);

        Doctor::create($request->all());

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor creado correctamente');
    }

    /**
     * DETALLE
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'speciality']);

        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * EDITAR
     */
    public function edit(Doctor $doctor)
    {
        $specialities = Speciality::all();

        return view('admin.doctors.edit', compact(
            'doctor',
            'specialities'
        ));
    }

    /**
     * ACTUALIZAR
     */
    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'medical_license_number' => 'required|string|max:50',
            'speciality_id' => 'nullable|exists:specialities,id',
            'biography' => 'nullable|string',
        ]);

        $doctor->update([
            'medical_license_number' =>
                $request->medical_license_number,
            'speciality_id' =>
                $request->speciality_id,
            'biography' =>
                $request->biography,
        ]);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Información actualizada',
            'text' => 'Datos del médico se actualizaron correctamente',
        ]);

        return redirect()
            ->route('admin.doctors.index', $doctor);
    }

    /**
     * ELIMINAR
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor eliminado');
    }
}