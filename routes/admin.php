<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController; 
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\SupportController;

Route::get('/', function(){
    return view('admin.dashboard');
})->name('dashboard');


// Gestión de roles
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class)->names('users');
Route::resource('patients', PatientController::class);
Route::resource('doctors', DoctorController::class)->names('doctors');
Route::resource('support', SupportController::class)->names('support');