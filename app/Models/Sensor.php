<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensor';
    
    protected $fillable = [
        'alat_id','project','record_at', 'ldr','relay_1','raw_data'
    ];

    protected $casts = [
        'record_at' =>'datetime',
        'raw_data' =>'array',
        'relay_1' => 'boolean',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }
}

