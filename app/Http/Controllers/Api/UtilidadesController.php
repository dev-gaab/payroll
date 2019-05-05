<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Salario;
use App\Models\Utilidades;
use App\Models\Trabajador;
use Illuminate\Support\Facades\DB;

class UtilidadesController extends Controller
{

    public function todas($empresa_id) {
      $utilidades = DB::table('utilidades')
        ->join('trabajador', 'utilidades.trabajador_id', 'trabajador.id')
        ->join('empresa', 'trabajador.empresa_id', 'empresa.id')
        ->select('trabajador.cedula', 'trabajador.nombre1', 'trabajador.apellido1', 'trabajador.fecha_ingreso', 'utilidades.*')
        ->where('empresa.id', $empresa_id)
        ->get();

      return response()->json($utilidades);
    }

    public function ver($id) {
      $utilidades = DB::table('utilidades')
        ->join('trabajador', 'utilidades.trabajador_id', 'trabajador.id')
        ->select('trabajador.cedula', 'trabajador.nombre1', 'trabajador.apellido1', 'utilidades.*')
        ->where('utilidades.id', $id)
        ->first();

      return response()->json($utilidades);
    }

    public function addUtilidades(Request $request) {

      if ($request->dias_utilidades < 30)
        return response()->json(["error" => "Los dias de utilidades no pueden ser menores a 30"]);

      if($request->dias_utilidades > 120)
        return response()->json(["error" => "Los dias de utilidades no pueden ser mayores a 120 dias (4 meses)"]);

      $comprobar_utilidades = Utilidades::where('trabajador_id', $request->id)
        ->whereYear('fecha', date('Y'))
        ->first();

      if($comprobar_utilidades != null)
        return response()->json(["error" => "El trabajador ya recibio utilidades por este año."]);

      $salario = Salario::where('trabajador_id', $request->id)
    		->where('estatus', 'activo')
        ->first();

      $trabajador = Trabajador::find($request->id);
      $fecha = $trabajador->fecha_ingreso;
      $fecha = explode('-', $fecha);
      $year_ingreso = $fecha[0];
      $tipo = 'fraccionada';

      if($request->fraccionada) {
        if($year_ingreso < date('Y')) {
          $dias = ($request->dias_utilidades * (int) date('m')) / 12;
          if(date('m') == 12) $tipo = 'completa';
          $meses = (int) date('m');
        } else {
          $fecha1 = new \DateTime($trabajador->fecha_ingreso);
          $fecha2 = new \DateTime(date('Y-m-d'));

          $dif = $fecha1->diff($fecha2);
          $meses = $dif->m;
          if($dif->d >= 5) $meses ++;

          if($meses == 12) $tipo = 'completa';

          $dias = ($request->dias_utilidades * $meses) / 12;
        }
      } else {
        $meses = 12;
        $dias = $request->dias_utilidades;
        $tipo = 'completa';
      }

      $monto = $dias * $salario->salario_diario;

      $utilidades = new Utilidades();
      $utilidades->trabajador_id = $request->id;
      $utilidades->fecha = date('Y-m-d');
      $utilidades->dias = $request->dias_utilidades;
      $utilidades->monto = $monto;
      $utilidades->sd_actual = $salario->salario_diario;
      $utilidades->tipo = $tipo;
      $utilidades->meses_calculados = $meses;


      $utilidades->save();

      return response()->json(["res" => "Done!"]);
    }

    public function disponibles($empresa_id) {
      $trabajadores = Trabajador::where('empresa_id', $empresa_id)
        ->where('estatus', 'activo')
        ->get();

      $disponibles = [];
      foreach ($trabajadores as $trabajador) {
        $comprobar_utilidades = Utilidades::where('trabajador_id', $trabajador["id"])
        ->whereYear('fecha', date('Y'))
        ->first();

        if($comprobar_utilidades == null) {
          $disponibles[] = $trabajador;
        }
      }

      return response()->json($disponibles);
    }


}
