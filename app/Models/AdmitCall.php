<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmitCall extends Model
{
    use HasFactory;

    protected $fillable = ['an', 'found', 'retry'];

    protected $casts = ['an' => 'integer'];
}
