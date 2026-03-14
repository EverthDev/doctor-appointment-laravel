<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController; 
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\SupportController;

Route::get('/', function(){
    return view('admin.dashboard');
})->name('dashboard');


// Gestión de roles
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class)->names('users');
Route::resource('patients', PatientController::class);
Route::resource('doctors', DoctorController::class)->names('doctors');
Route::get('doctors/{doctor}/schedules', [DoctorScheduleController::class, 'index'])->name('doctors.schedules');

// Citas (Appointments)
Route::resource('appointments', AppointmentController::class)->names('appointments');
Route::get('appointments/{appointment}/consultation', \App\Livewire\Admin\ConsultationManager::class)->name('appointments.consultation');

// Support Routes.
Route::resource('support', SupportController::class)->names('support');