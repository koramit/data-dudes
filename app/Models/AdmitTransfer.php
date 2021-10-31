<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmitTransfer extends Model
{
    use HasFactory;

    protected $fillable = ['admission_id', 'ward_id', 'attending_staff_id'];
}
