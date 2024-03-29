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

      for($i = 0 ; $i < sizeof($vacaciones); $i++) {
          $vacaciones[$i]->montos = json_decode($vacaciones[$i]->montos);
        }
      return response()->json($vacaciones);
    }

    public function find($id) {
      $vacacion = DB::table('vacaciones')
        ->join('trabajador', 'vacaciones.trabajador_id', 'trabajador.id')
        ->select('trabajador.cedula', 'trabajador.nombre1', 'trabajador.apellido1', 'vacaciones.*')
        ->where('vacaciones.id', $id)
        ->first();
      return response()->json($vacacion);
    }

    public function agregar(Request $request) {

      // $validar_disponibilidad = $this->validarDisponibilidadVacaciones($request->id, $request->fecha_inicio);

      // if(!$validar_disponibilidad)
      //   return response()->json(["error" => "Trabajador no cumple con el tiempo para el beneficio de vacaciones", "meses" => $validar_disponibilidad["meses"]}]);

      $salario = Salario::where('trabajador_id', $request->id)
        ->where('estatus', 'activo')
        ->select('salario_diario')
        ->first();

      $trabajador = Trabajador::find($request->id);

      $fecha_ingreso = new \DateTime($trabajador->fecha_ingreso);
      $fecha_actual = new \Datetime(date('Y-m-d'));
      $diferencia = $fecha_ingreso->diff($fecha_actual);
      $years_servicio = $diferencia->y;

      $dias_disfrute = (15 + $years_servicio) - 1;
      $bono_vacacional = 15 + $years_servicio;

      if ($dias_disfrute > 30) $dias_disfrute = 30;
      if ($bono_vacacional > 30) $bono_vacacional = 30;

      if($request->isFraccionada) {
        $fecha_egreso = new \DateTime($trabajador->fecha_egreso);
        $dif = $fecha_ingreso->diff($fecha_egreso);

        $meses = $dif->m;

        if($dif->d >= 5) $meses ++;

        $dias_disfrute = ($dias_disfrute * $meses) / 12;
      }

      $fecha_final = $this->calcularFechaFinal(round($dias_disfrute), $request->dias_feriados, $request->fecha_inicio);

      $total_dias_vac = round($dias_disfrute) + $request->dias_feriados + $fecha_final["dias_descanso"];
      $dias_a_pagar = $total_dias_vac + $bono_vacacional;

      $cesta_ticket = CestaTicket::where('estatus', 'activa')->first();

      $monto_cesta_ticket = ($cesta_ticket->cantidad/30) * $dias_disfrute;

      $total_pagar = ($dias_a_pagar * $salario->salario_diario) + $monto_cesta_ticket;

      $montos = [
        "total_dias_vacaciones" => $total_dias_vac,
        "bono_vacacional" => $bono_vacacional,
        "dias_a_pagar" => $dias_a_pagar,
        "cesta_ticket" => $monto_cesta_ticket,
        "total_pagar" => $total_pagar
      ];

      $vacaciones = new Vacaciones();
      $vacaciones->trabajador_id = $request->id;
      $vacaciones->a_servicio = $years_servicio;
      $vacaciones->dias_disfrute = round($dias_disfrute, 2);
      $vacaciones->dias_feriados = $request->dias_feriados;
      $vacaciones->dias_descanso = $fecha_final["dias_descanso"];
      $vacaciones->fecha_inicial = $request->fecha_inicio;
      $vacaciones->fecha_final = $fecha_final["fecha_final"];
      $vacaciones->tipo = $request->tipo;
      $vacaciones->montos = json_encode($montos);

      $vacaciones->save();

      return response()->json(["res" => "Done!"]);

    }

    public function modificar($id, Request $request) {
      $salario = Salario::where('trabajador_id', $request->trabajador_id)
        ->where('estatus', 'activo')
        ->select('salario_diario')
        ->first();

      $fecha_final = $this->calcularFechaFinal(round($request->dias_disfrute), $request->dias_feriados, $request->fecha_inicial);

      $total_dias_vac = round($request->dias_disfrute) + $request->dias_feriados + $fecha_final["dias_descanso"];
      // $dias_a_pagar = $total_dias_vac + $bono_vacacional;

      // $cesta_ticket = CestaTicket::where('estatus', 'activa')->first();

      // $monto_cesta_ticket = ($cesta_ticket->cantidad/30) * $dias_disfrute;

      $total_pagar = $request->dias_disfrute * $salario->salario_diario;

      $montos = [
        "total_dias_vacaciones" => $total_dias_vac,
        "total_pagar" => $total_pagar
      ];

      $vacaciones = Vacaciones::find($id);
      $vacaciones->dias_feriados = $request->dias_feriados;
      $vacaciones->dias_descanso = $fecha_final["dias_descanso"];
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

    public function trabajadoresDisponibles($empresa_id) {
      $trabajadores_empr = Trabajador::where('empresa_id', $empresa_id)
        ->where('estatus', 'activo')
        ->select('id', 'cedula', 'nombre1', 'apellido1', 'fecha_ingreso')
        ->get();


      $trabajadores_disponibles = [];

      foreach ($trabajadores_empr as $trabajador) {
        $respuesta = $this->validarDisponibilidadVacaciones($trabajador, date('Y-m-d'));

        if($respuesta['res'] == true) {
          $trabajador['a_servicio'] = $respuesta['a_servicio'];
          $trabajadores_disponibles[] = $trabajador;

        }

      }

      return response()->json($trabajadores_disponibles);
    }

    public function validarDisponibilidadVacaciones($trabajador, $fecha_vacaciones)
    {
      $vacaciones_trabajador = Vacaciones::where('trabajador_id', $trabajador["id"])
        ->orderBy('fecha_inicial', 'DESC')
        ->first();


      if ($vacaciones_trabajador == null) {

        $fecha_ingreso_trabajador = new \DateTime($trabajador["fecha_ingreso"]);
        $fecha_vacaciones = new \DateTime($fecha_vacaciones);
        $diferencia = $fecha_ingreso_trabajador->diff($fecha_vacaciones);

        if($diferencia->y == 0) return ['res'=> false,'meses' => $diferencia->m];

        return ['res'=> true, 'a_servicio' => $diferencia->y];
      }

      $fecha_ingreso_trabajador = new \DateTime($trabajador["fecha_ingreso"]);


      $fecha_ultimas_vacaciones = new \DateTime($vacaciones_trabajador->fecha_inicial);
      $fecha_nuevas_vacaciones = new \DateTime($fecha_vacaciones);
      $diferencia_fechas = $fecha_ultimas_vacaciones->diff($fecha_nuevas_vacaciones);

      if($diferencia_fechas->y == 0) return ['res'=> false, 'a_servicio' => $diferencia_fechas->y, 'meses' => $diferencia_fechas->m];

      $a_servicio = $fecha_ingreso_trabajador->diff($fecha_ultimas_vacaciones);
      return ['res'=> true, 'a_servicio' => $a_servicio->y];
    }


    public function calcularFechaFinal($dias_validos, $dias_feriados, $fecha) {

      // colocar dias feriados y dias descanso..
      $dias_descanso = 0;

      while ($dias_feriados > 0) {
        $weekday = date('w', strtotime($fecha));

        if($weekday == 0 || $weekday == 6) {
          $dias_descanso ++;
        } else {
          $dias_feriados --;
        }

        $fecha = date("Y-m-d", strtotime($fecha . "+ 1 day"));
      }

      while ($dias_validos > 0) {
        $weekday = date('w', strtotime($fecha));

        if($weekday == 0 || $weekday == 6) {
          $dias_descanso ++;
          $fecha = date("Y-m-d", strtotime($fecha . "+ 1 day"));
        } else {
          $dias_validos --;

          if($dias_validos > 0 ) {
            $fecha = date("Y-m-d", strtotime($fecha . "+ 1 day"));

          }
        }

      }

      return ["fecha_final" => $fecha, "dias_descanso" => $dias_descanso];
    }


    public function delete($id) {
      $vacacion = Vacaciones::find($id);

      $vacacion->delete();
      return response()->json(["res" => "Done!"]);
    }

    /**
     * *VACACIONES FRACCIONADAS
     */

    public function vacacionesFracDisponibles($empresa_id) {
      $trabajadores = Trabajador::where('estatus', '<>', 'activo')
        ->select('id', 'cedula', 'nombre1', 'apellido1', 'fecha_ingreso', 'fecha_egreso')
        ->get();

      $disponibles = [];

      foreach ($trabajadores as $trabajador) {
        $vacacion = Vacaciones::where('trabajador_id', $trabajador['id'])
          ->where('tipo', '<>', 'anual')
          ->first();

        if($vacacion == null) {
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

}

