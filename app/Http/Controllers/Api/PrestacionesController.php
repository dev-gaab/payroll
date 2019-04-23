<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prestaciones;
use App\Models\Trabajador;
use App\Models\Salario;
use App\Models\Utilidades;

// use Faker\Provider\zh_CN\DateTime;

class PrestacionesController extends Controller
{

    public function calcularPrestaciones($trabajador_id, Request $request) {
        // verificamos sino existe un calculo de prestaciones
        $verificar = Prestaciones::where('trabajador_id', $trabajador_id)->first();

        if($verificar != null) return response()->json(["error" => "El trabajador ya posee un calculo de prestaciones sociales"]);

        $trabajador = Trabajador::find($trabajador_id);

        if($trabajador->estatus == 'activo') return response()->json(["error" => "El trabajador aun se encuentra activo, Payroll solo permite el calculo de prestaciones cuando se rompe relaciones laborales."]);

        $fecha_ingreso = new DateTime($trabajador->fecha_ingreso);
        $fecha_egreso = new DateTime($trabajador->fecha_egreso);
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

        $salario = Salario::where('trabajador_id', $trabajador_id)
          ->orderBy('hasta', 'DESC')
          ->first();

        $utilidades = (($request->dias_utilidades / 12) / 30) * $salario->salario_diario;
        $vacaciones = (($dias_vacaciones / 12) / 30) * $salario->salario_diario;
        $salario_integral = $utilidades + $vacaciones + $salario->salario_diario;

        $total_pagar = $total_dias * $salario_integral;

        $montos = [
          "total_dias" => $total_dias,
          "total_pagar" => $total_pagar,
          "salario_integral" => $salario_integral
        ];

        $prestaciones = new Prestaciones();

        $prestaciones->trabajador_id = $trabajador_id;
        $prestaciones->a_servicio = $years;
        $prestaciones->meses_servicio = $meses;
        $prestaciones->dias_servicio = $diferencia->d;
        $prestaciones->fecha = date('Y-m-d');
        $prestaciones->montos = json_encode($montos);

        $prestaciones->save();
    }
}
