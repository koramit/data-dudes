<?php

namespace App\Http\Controllers;

use App\Models\Ward;

class WardsSummaryController extends Controller
{
    public function index()
    {
        return Ward::withCount(['admissions' => function ($query) {
                        $query->whereNull('dismissed_at');
                    }])
                    ->get()
                    ->transform(fn ($w) => [
                        'id' => $w->id, 
                        'name' => $w->name, 
                        'admissions_count' => $w->admissions_count
                    ]);
    }
}
