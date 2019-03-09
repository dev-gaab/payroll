<?php

namespace App\Http\Controllers\Api;

use App\Models\CestaTicket;
use App\Models\Salario;
use App\Models\Trabajador;
use App\Models\Vacaciones;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VacacionesController extends Controller
{
    public function index($empresa_id) {
      $vacaciones = Vacaciones::where('empresa_id', $empresa_id);

      return response()->json(["vacaciones" => $vacaciones]);
    }

    public function find($id) {
      $vacacion = Vacaciones::find($id);

      return response()->json($vacacion);
    }

    public function agregar(Request $request) {
      $salario = Salario::where('trabajador_id'. $request->id)
        ->where('estatus', 'activo')
        ->select('salario_diario')
        ->first();

      // TODO: calcular años de servicio con la fecha de ingreso del trabajador y la fecha de inicio de las vacaciones.
      $years_servicio = 1; #un ejemplo

      $dias_disfrute = (15 * $years_servicio) - 1;
      $bono_vacacional = 15 + $years_servicio;

      if ($dias_disfrute > 30) $dias_disfrute = 30;
      if ($bono_vacacional > 30) $bono_vacacional = 30;

//      TODO: buscar funcion la cual permita acceder al calendario de venezuela y ver los dias feriados del año.
      $fecha = $request->fecha_inicio;
      $dias_validos = $dias_disfrute;
      $dias_feriados = 0;
      while ($dias_validos > 0) {
        $weekday = date('w', strtotime($fecha));
        if($weekday == 0 || $weekday == 6) {
          $dias_feriados ++;
          $fecha = date("d-m-Y", strtotime($fecha . "+ 1 day"));
        } else {
//          TODO: aqui va la funcion de el calendario para calcular los dias feriados.
          if($is_feriado = true) {
            $dias_feriados ++;
            $fecha = date("d-m-Y", strtotime($fecha . "+ 1 day"));
          } else {
            $dias_validos --;
          }
        }

      }

      $fecha_final = $fecha;
      $total_dias_vac = $dias_disfrute + $dias_feriados;
      $dias_a_pagar = $total_dias_vac + $bono_vacacional;

      $cesta_ticket = CestaTicket::where('estatus', 'activa')->first();

      $monto_cesta_ticket = ($cesta_ticket->cantidad/30) * $dias_disfrute;

      $total_pagar = ($dias_a_pagar * $salario->salario_diario) + $monto_cesta_ticket;

//      TODO: Guardar todos los datos en la tabla de vacaciones.
//      TODO: Guardar los montos en un json al igual que en nomina detalle
    }
}
