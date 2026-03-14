<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('admin.appointments.index');
    }

    public function create()
    {
        return view('admin.appointments.create');
    }

    public function store(Request $request)
    {
        // Creation handled by Livewire AppointmentCreator
        abort(404);
    }

    public function show(Appointment $appointment)
    {
        return redirect()->route('admin.appointments.edit', $appointment);
    }

    public function edit(Appointment $appointment)
    {
        return view('admin.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:1,2,3,4',
            'reason' => 'nullable|string',
        ]);

        $appointment->update($request->only('status', 'reason'));

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita actualizada',
            'text' => 'Los datos de la cita han sido guardados correctamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita eliminada',
            'text' => 'La cita fue eliminada con éxito.',
        ]);

        return redirect()->route('admin.appointments.index');
    }
}
