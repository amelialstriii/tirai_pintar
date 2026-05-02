<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = "alat";

    protected $fillable = [
        'project', 'device_code'
    ];

    public function sensor()
    {
        return $this->hasMany(Sensor::class, 'alat_id');
    }
}
