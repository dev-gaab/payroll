<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Configuraciones;

class ConfiguracionesController extends Controller
{
    public function listar($empresa_id) {
    	$configuraciones = Configuraciones::where('empresa_id', $empresa_id)->get();

    	return response()->json($configuraciones);
    }

    public function ver($id) {
    	$configuracion = Configuraciones::find($id);
    	
    	return response()->json($configuracion);
    }

    public function save(Request $request) {
    	$configuracion = new Configuraciones();

    	$configuracion->dias_utilidades = $request->dias_utilidades;
    	$configuracion->ivss = $request->ivss;
    	$configuracion->faov = $request->faov;
    	$configuracion->paro_forzoso = $request->paro_forzoso;

    	$configuracion->save();

    	return response()->json(["res" => "done!"]);
    }
}
