<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Salario;

class UtilidadesController extends Controller
{
    public function addUtilidades($trabajador_id) {
    	$salario = Salario::where('trabajador_id', $trabajador_id)
    		->where('estatus', 'activo')
    		->first();

    	 hj
    }
}