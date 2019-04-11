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
      /**
        * $fecha1 = new DateTime($fecha1);
        * $fecha2 = new DateTime($fecha2);
        * $diferencia = $fecha1->diff($fecha2);
        * $diferencia->y; #años de diferencia entre dos fechas.
      */


      $years_servicio = 1; #un ejemplo

      $dias_disfrute = (15 * $years_servicio) - 1;
      $bono_vacacional = 15 + $years_servicio;

      if ($dias_disfrute > 30) $dias_disfrute = 30;
      if ($bono_vacacional > 30) $bono_vacacional = 30;

      $fecha = $request->fecha_inicio;

      $dias_validos = $dias_disfrute;
      $dias_feriados = 0;
//      TODO: Separar en una funcion
      $feriados = [
        '01-01',
        '04-03',
        '05-03',
        '10-02',
        '21-05',
        '29-06',
        '16-07',
        '15-08',
        '18-09',
        '19-09',
        '12-10',
        '31-10',
        '01-11',
        '08-12',
        '13-12',
        '25-12'
      ];
      while ($dias_validos > 0) {
        $weekday = date('w', strtotime($fecha));
        if($weekday == 0 || $weekday == 6) {
          $dias_feriados ++;
          $fecha = date("d-m-Y", strtotime($fecha . "+ 1 day"));
        } else {
//          TODO: aqui va la funcion de el calendario para calcular los dias feriados.
          $fecha_separada = explode("-", $fecha);
          $dia_mes = $fecha_separada[2]."-".$fecha_separada[1]; // deberia devolver DIA-MES

          if(in_array($dia_mes, $feriados)){
            return false;
          }
          else{
            return true;
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
