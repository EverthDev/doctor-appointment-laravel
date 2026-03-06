<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles=Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
       $data=$request->validate([
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|string|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'password_confirmation' => 'required|string|min:8|same:password',
        'id_number' => 'required|string|min:5|max:255|regex:/^[A-za-z0-9]+$/|unique:users',
        'phone' => 'required|digits_between:7,15',
        'address' => 'required|string|min:3|max:255',
        'role_id' => 'required|exists:roles,id',
       ]);

       $user=User::create($data);
       $user->roles()->attach($request->role_id);
       session()->flash('swal', 
       [
        'icon' => 'success',
        'title' => 'Usuario creado correctamente',
        'text' => 'El usuario ha sido creado exitosamente',
       ]);

         //si el usuario creado es paciente, enviar al módulo pacientes
        if ($user->hasRole('Paciente')) {
            //creamos el registro de un paciente
            $patient = $user->patient()->create([]);
            return redirect()->route('admin.patients.edit', $patient);
        }
        // Crear perfil doctor automáticamente
        if ($user->hasRole('Doctor')) {

            $doctor = $user->doctor()->create([
                'medical_license_number' => '',
                'speciality_id' => null,
                'biography' => null,
            ]);
            return redirect()->route('admin.doctors.edit', $doctor);
        }

       return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente');
    }

    public function edit(User $user)
    {
        $roles=Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
       $data=$request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'id_number' => 'required|string|min:5|max:255|regex:/^[A-za-z0-9]+$/|unique:users,id_number,' . $user->id,
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role_id' => 'required|exists:roles,id',
           ]);
    
           $user->update($data);
           //Si el usuario quiere editar su contraseña, que lo guarde
           if ($request->has('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
           }
           $user->roles()->sync($data['role_id']);

           session()->flash('swal', 
           [
            'icon' => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text' => 'El usuario ha sido actualizado exitosamente',
           ]);
           return redirect()->route('admin.users.edit', $user->id)->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(User $user)
    {
    // Autorizar la acción usando la Policy
    // Esto lanzará automáticamente un 403 si la policy retorna false
    $this->authorize('delete', $user);
    // No permitir que el usuario logueado se borre a sí mismo 
    if (Auth::id()==$user->id){
        abort(403,'No puees borrar tu propio usuario');
    }
        session()->flash('swal', 
        [
            'icon' => 'success',
            'title' => 'Usuario eliminado correctamente',
            'text' => 'El usuario ha sido eliminado exitosamente',
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente');
    }
}
