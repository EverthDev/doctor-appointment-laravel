<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    //Relacion 1:M
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
