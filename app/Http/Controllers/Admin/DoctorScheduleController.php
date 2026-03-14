<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function index(Doctor $doctor)
    {
        return view('admin.doctors.schedules', compact('doctor'));
    }
}
