<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_short'];

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function activeAdmissions()
    {
        return $this->admissions->whereNull('dismissed_at');
    }
}
