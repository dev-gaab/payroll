<?php

namespace App\Http\Controllers\Api;

use App\Models\CestaTicket;
use App\Models\Salario;
use App\Models\Trabajador;
use App\Models\Vacaciones;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
// use Faker\Provider\cs_CZ\DateTime;

class VacacionesController extends Controller
{
    public function index($empresa_id) {

      $vacaciones = DB::table('vacaciones')
        ->join('trabajador', 'vacaciones.trabajador_id', 'trabajador.id')
        ->join('empresa', 'trabajador.empresa_id', 'empresa.id')
        ->select('trabajador.cedula', 'trabajador.nombre1', 'trabajador.apellido1', 'vacaciones.*')
        ->where('empresa.id', $empresa_id)
        ->get();
      return response()->json($vacaciones);
    }

    public function find($id) {
      $vacacion = DB::table('vacaciones')
        ->join('trabajador', 'vacaciones.trabajador_id', 'trabajador.id')
        ->select('trabajador.cedula', 'trabajador.nombre1', 'trabajador.apellido1', 'vacaciones.*')
        ->where('vacaciones.id', $id)
        ->get();
      return response()->json($vacacion);
    }

    public function agregar(Request $request) {

      $validar_disponibilidad = $this->validarDisponibilidadVacaciones($request->id, $request->fecha_inicio);

      // if(!$validar_disponibilidad)
      //   return response()->json(["error" => "Trabajador no cumple con el tiempo para el beneficio de vacaciones", "meses" => $validar_disponibilidad["meses"]}]);

      $salario = Salario::where('trabajador_id'. $request->id)
        ->where('estatus', 'activo')
        ->select('salario_diario')
        ->first();

      $trabajador = Trabajador::find($request->id);

      $fecha_ingreso = new DateTime($trabajador->fecha_ingreso);
      $fecha_actual = new Datetime(date('Y-m-d'));
      $diferencia = $fecha_ingreso->diff($fecha_actual);
      $years_servicio = $diferencia->y;

      $dias_disfrute = (15 + $years_servicio) - 1;
      // $bono_vacacional = 15 + $years_servicio;

      if($request->isFraccionada) {
        $dias_disfrute = ($dias_disfrute * $request->meses) / 12;
      }

      if ($dias_disfrute > 30) $dias_disfrute = 30;
      // if ($bono_vacacional > 30) $bono_vacacional = 30;

      $fecha_final = $this->calcularFechaFinal(round($dias_disfrute), $request->dias_feriados, $request->fecha_inicio);

      $total_dias_vac = round($dias_disfrute) + $fecha_final["dias_feriados"];
      // $dias_a_pagar = $total_dias_vac + $bono_vacacional;

      // $cesta_ticket = CestaTicket::where('estatus', 'activa')->first();

      // $monto_cesta_ticket = ($cesta_ticket->cantidad/30) * $dias_disfrute;

      $total_pagar = $dias_disfrute * $salario->salario_diario;

      $montos = [
        "total_dias_vacaciones" => $total_dias_vac,
        "total_pagar" => $total_pagar
      ];

      $vacaciones = new Vacaciones();
      $vacaciones->trabajador_id = $request->id;
      $vacaciones->a_servicio = $years_servicio;
      $vacaciones->dias_disfrute = $dias_disfrute;
      $vacaciones->dias_feriados = $fecha_final["dias_feriados"];
      $vacaciones->fecha_inicial = $request->fecha_inicio;
      $vacaciones->fecha_final = $fecha_final["fecha_final"];
      $vacaciones->montos = json_encode($montos);

      $vacaciones->save();

      return response()->json(["res" => "Done!"]);

    }

    public function agregarDiasFeriados ($id, Request $request) {
      $vacaciones = Vacaciones::find($id);
      $fecha = $vacaciones->fecha_final;
      $feriados_adicionales = $request->feriados;

      while ($feriados_adicionales > 0) {
        $weekday = date('w', strtotime($fecha));

        if($weekday == 0 || $weekday == 6) {
          $vacaciones->feriados ++;
        } else {
          $feriados_adicionales --;
        }

        $fecha = date("Y-m-d", strtotime($fecha . "+ 1 day"));
      }

      $vacaciones->fecha_final = $fecha;

      $vacaciones->save();

      return response()->json(["res" => "Done!"]);

    }

    public function validarDisponibilidadVacaciones($trabajador_id, Request $request)
    {
      $vacaciones_trabajador = Vacaciones::where('trabajador_id', $trabajador_id)
        ->orderBy('fecha_inicial', 'DESC')
        ->first();
      $trabajador = Trabajador::find($trabajador_id);

      if ($vacaciones_trabajador == null) {
        $fecha_ingreso_trabajador = new DateTime($trabajador->fecha_ingreso);
        $fecha_vacaciones = new DateTime($request->fecha_vacaciones);
        $diferencia = $fecha_ingreso_trabajador->diff($fecha_vacaciones);

        if($diferencia->y == 0) return response()->json(['res'=> false, 'meses' => $diferencia->m]);

        return response()->json(['res'=> true]);
      }

      $fecha_ultimas_vacaciones = new DateTime($vacaciones_trabajador->fecha_inicial);
      $fecha_nuevas_vacaciones = new DateTime($request->fecha_vacaciones);
      $diferencia_fechas = $fecha_ultimas_vacaciones->diff($fecha_nuevas_vacaciones);

      if($diferencia_fechas->y == 0) return response()->json(['res'=> false, 'meses' => $diferencia_fechas->m]);

      return response()->json(['res'=> true]);
    }

    public function calcularFechaFinal($dias_validos, $dias_feriados, $fecha) {
      $feriados_iniciales = $dias_feriados;

      while ($feriados_iniciales > 0) {
        $weekday = date('w', strtotime($fecha));

        if($weekday == 0 || $weekday == 6) {
          $dias_feriados ++;
        } else {
          $feriados_iniciales --;
        }

        $fecha = date("Y-m-d", strtotime($fecha . "+ 1 day"));
      }

      while ($dias_validos > 0) {
        $weekday = date('w', strtotime($fecha));

        if($weekday == 0 || $weekday == 6) {
          $dias_feriados ++;
          $fecha = date("Y-m-d", strtotime($fecha . "+ 1 day"));
        } else {
          $dias_validos --;

          if($dias_validos > 0 ) {
            $fecha = date("Y-m-d", strtotime($fecha . "+ 1 day"));

          }
        }

      }

      return ["fecha_final" => $fecha, "dias_feriados" => $dias_feriados];
    }

}

