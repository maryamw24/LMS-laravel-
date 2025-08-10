<?php

namespace App\Http\Controllers;

use App\Models\Lookup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LookupController extends Controller
{
    public function index()
    {
        return response()->json([
            'genders' => Lookup::where('Type', 'Gender')->get(),
            'departments' => Lookup::where('Type', 'Department')->get(),
        ]);
    }

    
}

