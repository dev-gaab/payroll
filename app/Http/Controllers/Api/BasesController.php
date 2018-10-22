<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseLegal;

class BasesController extends Controller
{
    // ver todas las bases legales de una empresa.
    public function verTodas($id)
    {
        $bases =  BaseLegal::where('empresa_id', $id)
            ->orderBy('estatus', 'asc')
            ->get();
        
        return response()->json(['bases' => $bases]);
    }
}
