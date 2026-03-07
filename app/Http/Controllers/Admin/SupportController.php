<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * LISTADO
     */
    public function index()
    {
        return view('admin.support.index');
    }

    /**
     * FORM CREAR
     */
    public function create()
    {
        return view('admin.support.create');
    }

    /**
     * GUARDAR
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Support::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'open',
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Ticket creado correctamente',
            'text' => 'El ticket ha sido registrado',
        ]);

        return redirect()->route('admin.support.index');
    }

    /**
     * DETALLE
     */
    public function show(Support $support)
    {
        $support->load('user');

        return view('admin.support.show', compact('support'));
    }

    /**
     * EDITAR
     */
    public function edit(Support $support)
    {
        return view('admin.support.edit', compact('support'));
    }

    /**
     * ACTUALIZAR
     */
    public function update(Request $request, Support $support)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $support->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Información actualizada',
            'text' => 'El ticket ha sido actualizado correctamente',
        ]);

        return redirect()->route('admin.support.index');
    }

    /**
     * ELIMINAR
     */
    public function destroy(Support $support)
    {
        $support->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Ticket eliminado',
            'text' => 'El ticket ha sido eliminado correctamente',
        ]);

        return redirect()->route('admin.support.index');
    }
}
