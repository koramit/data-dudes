<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stay extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'medicine_consulted_at' => 'datetime',
        'encountered_at' => 'datetime',
        'dismissed_at' => 'datetime',
    ];

    public function notes()
    {
        return $this->hasMany('App\Models\StayNote', 'stay_id', 'id');
    }
}
