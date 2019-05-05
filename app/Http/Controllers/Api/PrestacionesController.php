<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prestaciones;
use App\Models\Trabajador;
use App\Models\Salario;
use App\Models\Utilidades;
use Illuminate\Support\Facades\DB;

// use Faker\Provider\zh_CN\DateTime;

class PrestacionesController extends Controller
{

    public function calcularPrestaciones(Request $request) {
        // verificamos sino existe un calculo de prestaciones
        $verificar = Prestaciones::where('trabajador_id', $request->id)->first();

        if($verificar != null) return response()->json(["error" => "El trabajador ya posee un calculo de prestaciones sociales"]);

        $trabajador = Trabajador::find($request->id);

        if($trabajador->estatus == 'activo') return response()->json(["error" => "El trabajador aun se encuentra activo, Payroll solo permite el calculo de prestaciones cuando se rompen relaciones laborales."]);

        $fecha_ingreso = new \DateTime($trabajador->fecha_ingreso);
        $fecha_egreso = new \DateTime($trabajador->fecha_egreso);
        $diferencia = $fecha_ingreso->diff($fecha_egreso);

        if($diferencia->d >= 5) {
          if($diferencia->m + 1 == 12) {
            $years = $diferencia->y + 1;
            $meses = 0;
          } else {
            $years = $diferencia->y;
            $meses = $diferencia->m + 1;
          }

        } else {
          $years = $diferencia->y;
          $meses = $diferencia->m;
        }

        if($meses > 0) {
          $dias_vacaciones = 15 + $years;
        } else {
          $dias_vacaciones = (15 + $years) - 1;
        }

        if($dias_vacaciones > 30) $dias_vacaciones = 30;

        $dias_years = 30 * $years;
        $dias_meses = (30 * $meses) / 12;
        $total_dias = $dias_years + $dias_meses;

        $salario = Salario::where('trabajador_id', $request->id)
          ->orderBy('hasta', 'DESC')
          ->first();

        $utilidades = (($request->dias_utilidades / 12) / 30) * $salario->salario_diario;
        $vacaciones = (($dias_vacaciones / 12) / 30) * $salario->salario_diario;
        $salario_integral = $utilidades + $vacaciones + $salario->salario_diario;

        $total_pagar = $total_dias * $salario_integral;

        $montos = [
          "dias_utilidades" => $request->dias_utilidades,
          "dias_vacaciones" => $dias_vacaciones,
          "salario_diario"  => $salario->salario_diario,
          "total_dias" => $total_dias,
          "total_pagar" => $total_pagar,
          "salario_integral" => $salario_integral
        ];

        $prestaciones = new Prestaciones();

        $prestaciones->trabajador_id = $request->id;
        $prestaciones->a_servicio = $diferencia->y;
        $prestaciones->meses_servicio = $diferencia->m;
        $prestaciones->dias_servicio = $diferencia->d;
        $prestaciones->fecha = date('Y-m-d');
        $prestaciones->montos = json_encode($montos);

        $prestaciones->save();

        return response()->json(["res" => "Done!"]);
    }

    public function disponibles($empresa_id) {
      $trabajadores = Trabajador::where('estatus', '<>', 'activo')
        ->select('id', 'cedula', 'nombre1', 'apellido1', 'fecha_ingreso', 'fecha_egreso')
        ->get();

        $disponibles = [];

      foreach($trabajadores as $trabajador) {
        $prestacion = Prestaciones::where('trabajador_id', $trabajador['id'])->first();

        if($prestacion == null) {
          $fecha_ingreso = new \DateTime($trabajador['fecha_ingreso']);
          $fecha_egreso = new \Datetime($trabajador['fecha_egreso']);

          $dif = $fecha_ingreso->diff($fecha_egreso);
          $a_servicio = $dif->y;
          $trabajador['a_servicio'] = $a_servicio;

          $disponibles[] = $trabajador;
        }
      }

      return response()->json($disponibles);
    }

    public function todas($empresa_id) {
      $trabajadores = DB::table('trabajador')
        ->join('prestaciones', 'trabajador.id', 'prestaciones.trabajador_id')
        ->select('trabajador.cedula', 'trabajador.nombre1', 'trabajador.apellido1', 'trabajador.fecha_ingreso', 'trabajador.fecha_egreso', 'prestaciones.*')
        ->get();

        for($i = 0 ; $i < sizeof($trabajadores); $i++) {
          $trabajadores[$i]->montos = json_decode($trabajadores[$i]->montos);
        }

      return response()->json($trabajadores);
    }

    public function delete($id) {
      $prestaciones = Prestaciones::find($id);
      $prestaciones->delete();

      return response()->json(["res" => "Done!"]);
    }

    public function ver($id) {
      $trabajador = DB::table('trabajador')
        ->join('prestaciones', 'trabajador.id', 'prestaciones.trabajador_id')
        ->where('prestaciones.id', $id)
        ->select('trabajador.cedula', 'trabajador.nombre1', 'trabajador.apellido1', 'prestaciones.*')
        ->first();

        return response()->json($trabajador);
    }
}
