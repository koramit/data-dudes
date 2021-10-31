<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendingStaff extends Model
{
    use HasFactory;

    protected $table = 'attending_staffs';

    protected $fillable = ['name', 'license_no'];
}
