<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Nomina;
use App\Models\NominaDetalle;
use App\Models\Trabajador;
use App\Models\Salario;

class NominaController extends Controller
{
  public function verTodas($id)
  {
    $nominas = Nomina::where('empresa_id', $id)->get();

    return response()->json($nominas);
  }
  public function generar($id, Request $request)
  {
    $nominas = Nomina::where('empresa_id', $id)->get();

    if(sizeof($nominas) > 0) {
      $nomina_activa = Nomina::where('empresa_id', $id)
        ->where('estatus', 'activa')
        ->first();

      $fecha_inicio = explode($nomina_activa->desde);

    }
  }
}
