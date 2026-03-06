<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        return view('admin.roles.index');
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        //Validar 
        $request->validate(['name' => 'required|unique:roles,name']);

        //Crear si ya pasó validación
        Role::create(['name' => $request->name]);

        //Variable de un solo uso para alerta
        session()->flash('swal',
        [
            'icon' => 'success',
            'title' => 'Rol creado correctamente',
            'text' => 'El rol ha sido creado exitosamente'
        ]);

        //redireccionar a la tabla principal
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(Role $role)
    {
        //Restringir la acción para los primeros 4 roles fijos
        if ($role->id <=4){
            //Variable de un solo uso 
            session()->flash('swal',[
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes editar este rol.',
            ]);
            return redirect()->route('admin.roles.index');
        }
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
        {
        //Validar 
        $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);
        
        //Si el campo no cambió no actualices
        if ($role->name === $request->name) {
            session()->flash('swal', 
            [
                'icon' => 'info',
                'title' => 'Sin cambios',
                'text' => 'No se detectaron modificaciones',
            ]);
            return redirect()->route('admin.roles.edit', $role);
        }

        //Editar si ya pasó validación
        $role->update(['name'=>$request->name]);

        //Variable de un solo uso para alerta
        session()->flash('swal',
        [
            'icon' => 'success',
            'title' => 'Rol actualizado correctamente correctamente',
            'text' => 'El rol ha sido actualizado exitosamente'
        ]);

        //redireccionar a la tabla principal
        return redirect()->route('admin.roles.index', $role);
    }

    public function destroy(Role $role)
    {
        //Restringir la acción para los primeros 4 roles fijos
        if ($role->id <=4){
            //Variable de un solo uso 
            session()->flash('swal',[
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar este rol.',
            ]);
            return redirect()->route('admin.roles.index');
        }
        //Borrar el elemento 
        $role->delete();

        //Alerta
        session()->flash('swal', 
        [
            'icon' => 'success',
            'title' => 'Rol eliminado correctamente',
            'text' => 'El rol ha sido eliminado exitosamente',
        ]);
        //Redireccionar al mismo lugar
        return redirect()->route('admin.roles.index');        

    }
}
