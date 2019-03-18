<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Nomina;
use App\Models\NominaDetalle;
use App\Models\Trabajador;
use App\Models\Salario;
use App\Models\CestaTicket;
use App\Models\SalarioMinimo;
use App\Models\Deducciones;
use App\Models\Asignaciones;

date_default_timezone_set("America/Caracas");

class NominaController extends Controller
{
  public function verTodas($id)
  {
    $nominas = Nomina::where('empresa_id', $id)
      ->orderBy('estatus')
      ->get();

    return response()->json($nominas);
  }

  public function ver($id)
  {
    $nomina = Nomina::find($id);

    $nominas_detalle = NominaDetalle::where('nomina_id', $nomina->id)->get();

    return response()->json(["nomina" => $nomina, "nominas_detalle" => $nominas_detalle]);
  }

  public function agregarNominaDetalle($nomina_id, $trabajador_id, $datos)
  {
    $salario = Salario::where('trabajador_id', $trabajador_id)
      ->where('estatus', 'activo')
      ->first();

    $asig = Asignaciones::where('estatus', 'activa')->first();
    $ded = Deducciones::where('estatus', 'activa')->first();
    $cest = CestaTicket::where('estatus', 'activa')->first();

    $costo_hora = $salario->salario_diario / 8;
    // Asignaciones
    $monto_he_diurnas = ($costo_hora * $asig->he_diurnas) * $datos['he_diurnas'];
    $monto_he_noct = ($costo_hora * $asig->he_nocturnas) * $datos['he_nocturnas'];
    $monto_feriados = ($salario->salario_diario * $asig->feriados) * $datos['feriados'];

    $pago = $datos['dias_trabajados'] * $salario->salario_diario;
    // deducciones
    $monto_ivss = $pago * ($ded->ivss / 100);

    $monto_faov = 0;
    if ($datos['faov'] == true) {
      $monto_faov = $pago * ($ded->faov / 100);
    }

    $monto_paro = 0;
    if ($datos['paro_forzoso'] == true) {
      $monto_paro = $pago * ($ded->paro_forzoso / 100);
    }

    // cesta ticket
    $monto_cest = ($cest->cantidad / 30) * $datos['dias_trabajados'];

    $total_asignaciones = $pago + $monto_he_diurnas + $monto_he_noct + $monto_feriados;
    $total_deducciones = $monto_ivss + $monto_faov + $monto_paro;
    $monto_total = ($total_asignaciones - $total_deducciones) + $monto_cest;

    $montos = [
      "he_diurnas" => $monto_he_diurnas,
      "he_nocturnas" => $monto_he_noct,
      "feriados" => $monto_feriados,
      "cesta_ticket" => $monto_cest,
      "ivss" => $monto_ivss,
      "faov" => $monto_faov,
      "paro_forzoso" => $monto_paro,
      "pago_salario" => $pago,
      "salario_diario"  => $salario->salario_diario,
      "cesta_ticket_mensual" => $cest->cantidad,
      "total_asignaciones" => $total_asignaciones,
      "total_deducciones" => $total_deducciones,
      "monto_total" => $monto_total
    ];

    $nomina_detalle = new NominaDetalle();
    $nomina_detalle->trabajador_id = $trabajador_id;
    $nomina_detalle->nomina_id = $nomina_id;
    $nomina_detalle->dias_trabajados = $datos['dias_trabajados'];
    $nomina_detalle->he_diurnas = $datos['he_diurnas'];
    $nomina_detalle->he_nocturnas = $datos['he_nocturnas'];
    $nomina_detalle->feriados = $datos['feriados'];
    $nomina_detalle->ivss = true;
    $nomina_detalle->faov = $datos['faov'];
    $nomina_detalle->paro_forzoso = $datos['paro_forzoso'];
    $nomina_detalle->montos = json_encode($montos);

    $nomina_detalle->save();

    return true;
  }

  public function validateFechaNomina($id)
  {
    $nomina_activa = Nomina::where('empresa_id', $id)
      ->where('estatus', 'activa')
      ->first();

    if ($nomina_activa != '' && date('Y-m-d') < $nomina_activa->hasta) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * TODO:
   * -[x] Realizar el else de si no existen nominas anteriores.
   * -[x] El "else" debera definir las fechas con el mismo nombre de variables que estan en el if.
   */
  public function generar($id, Request $request)
  {


    $nominas = Nomina::where('empresa_id', $id)->get();

    if (sizeof($nominas) > 0) {

      if($this->validateFechaNomina($id)) {
        return response()->json(["error" => "No se puede generar una nueva nomina, la nomina actual aun esta sin vencer"]);
      }

      $nomina_activa = Nomina::where('empresa_id', $id)
        ->where('estatus', 'activa')
        ->first();

      $fecha_nomina_anterior = explode('-', $nomina_activa->desde);
      $dia_nomina_anterior = $fecha_nomina_anterior[2];
      $mes_nomina_anterior = $fecha_nomina_anterior[1];
      $year_nomina_anterior = $fecha_nomina_anterior[0];

      if ($dia_nomina_anterior == "01") {
        $nro_nomina = "02";
        $dia_inicio = 15;
        $dia_final = date('t', strtotime($nomina_activa->desde));
        $mes = $mes_nomina_anterior;
        $year = $year_nomina_anterior;
      } else {
        $nro_nomina = "01";
        $dia_inicio = "01";
        $dia_final = 15;
        $mes = date("m", strtotime($nomina_activa->desde . "+ 1 month"));
        $year = date("Y", strtotime($nomina_activa->desde . "+ 1 month"));
      }
    } else {
      if (date('d') < 15) {
        $nro_nomina = "01";
        $dia_inicio = "01";
        $dia_final = 15;
      } else {
        $nro_nomina = "02";
        $dia_inicio = 15;
        $dia_final = date('t');
      }

      $mes = date("m");
      $year = date("Y");
    }

    $fecha_inicio = array($year, $mes, $dia_inicio);
    $fecha_final = array($year, $mes, $dia_final);

    $fecha_inicio = implode('-', $fecha_inicio);
    $fecha_final = implode('-', $fecha_final);

    $codigo_nomina = (string)$nro_nomina . $mes . $year;
    $asignaciones = Asignaciones::where('estatus', 'activa')->first();
    $deducciones = Deducciones::where('estatus', 'activa')->first();
    $cesta_ticket = CestaTicket::where('estatus', 'activa')->first();
    $salario_minimo = SalarioMinimo::where('estatus', 'activo')->first();

    $nomina = new Nomina();
    $nomina->empresa_id = $id;
    $nomina->codigo = $codigo_nomina;
    $nomina->desde = $fecha_inicio;
    $nomina->hasta = $fecha_final;
    $nomina->estatus = 'activa';
    $nomina->asignaciones_id = $asignaciones->id;
    $nomina->deducciones_id = $deducciones->id;
    $nomina->cesta_ticket_id = $cesta_ticket->id;
    $nomina->salario_minimo_id = $salario_minimo->id;
    $nomina->tipo = 'quincenal';

    if ($nomina->save()) {
      if(sizeof($nominas)) {
        $nomina_activa->estatus = 'historico';
        $nomina_activa->save();
      }
    }

    $trabajadores = Trabajador::where('empresa_id', $id)->get();
    $nro_trabajadores = sizeof($trabajadores);

    $datos_nom = [
      "he_diurnas" => 0,
      "he_nocturnas" => 0,
      "feriados" => 0,
      "dias_trabajados" => 15,
      "faov" => false,
      "paro_forzoso" => false
    ];

    for ($i = 0; $i < $nro_trabajadores; $i++) {
      $this->agregarNominaDetalle($nomina->id, $trabajadores[$i]->id, $datos_nom);
    }

    return response()->json(['msg' => 'done!']);
  }


  public function modificarNominaDetalle($nomina_id, $trabajador_id, Request $request)
  {
    $salario = Salario::where('trabajador_id', $trabajador_id)
      ->where('estatus', 'activo')
      ->first();

    $asig = Asignaciones::where('estatus', 'activa')->first();
    $ded = Deducciones::where('estatus', 'activa')->first();
    $cest = CestaTicket::where('estatus', 'activa')->first();

    $costo_hora = $salario->salario_diario / 8;
    // Asignaciones
    $monto_he_diurnas = ($costo_hora * $asig->he_diurnas) * $request->he_diurnas;
    $monto_he_noct = ($costo_hora * $asig->he_nocturnas) * $request->he_nocturnas;
    $monto_feriados = ($salario->salario_diario * $asig->feriados) * $request->feriados;

    $pago = $request->dias_trabajados * $salario->salario_diario;
    // deducciones
    $monto_ivss = $pago * ($ded->ivss / 100);

    $monto_faov = 0;
    if ($request->faov == true) {
      $monto_faov = $pago * ($ded->faov / 100);
    }

    $monto_paro = 0;
    if ($request->paro_forzoso == true) {
      $monto_paro = $pago * ($ded->paro_forzoso / 100);
    }

    // cesta ticket
    $monto_cest = ($cest->cantidad / 30) * $request->dias_trabajados;
    $total_asignaciones = $pago + $monto_he_diurnas + $monto_he_noct + $monto_feriados;
    $total_deducciones = $monto_ivss + $monto_faov + $monto_paro;
    $monto_total = ($total_asignaciones - $total_deducciones) + $monto_cest;

    $montos = [
      "he_diurnas" => $monto_he_diurnas,
      "he_nocturnas" => $monto_he_noct,
      "feriados" => $monto_feriados,
      "cesta_ticket" => $monto_cest,
      "ivss" => $monto_ivss,
      "faov" => $monto_faov,
      "paro_forzoso" => $monto_paro,
      "pago_salario" => $pago,
      "salario_diario"  => $salario->salario_diario,
      "cesta_ticket_mensual" => $cest->cantidad,
      "total_asignaciones" => $total_asignaciones,
      "total_deducciones" => $total_deducciones,
      "monto_total" => $monto_total
    ];

    $nomina_detalle = NominaDetalle::find($nomina_id);
    $nomina_detalle->dias_trabajados = $request->dias_trabajados;
    $nomina_detalle->he_diurnas = $request->he_diurnas;
    $nomina_detalle->he_nocturnas = $request->he_nocturnas;
    $nomina_detalle->feriados = $request->feriados;
    $nomina_detalle->ivss = true;
    $nomina_detalle->faov = $request->faov;
    $nomina_detalle->paro_forzoso = $request->paro_forzoso;
    $nomina_detalle->montos = json_encode($montos);

    $nomina_detalle->save();

    return response()->json(["msg" => "done!"]);
  }

  public function allNominaDetalle($id)
  {

    $nominas = DB::table('nomina_detalle')
      ->join('trabajador', 'nomina_detalle.trabajador_id', '=', 'trabajador.id')
      ->where("nomina_id", $id)
      ->select('trabajador.id as trabajador_id', 'trabajador.cedula', 'trabajador.nombre1 as nombre', 'trabajador.apellido1 as apellido', 'nomina_detalle.*')
      ->get();

    for($i = 0 ; $i < sizeof($nominas); $i++) {
      $nominas[$i]->montos = json_decode($nominas[$i]->montos);
    }
    return response()->json($nominas);
  }

  public function verNominaDetalle($id)
  {
    $nomina = DB::table('nomina_detalle')
      ->join('trabajador', 'nomina_detalle.trabajador_id', '=', 'trabajador.id')
      ->where("nomina_detalle.id", $id)
      ->select('trabajador.id as trabajador_id', 'trabajador.cedula', 'trabajador.nombre1 as nombre', 'trabajador.apellido1 as apellido', 'nomina_detalle.*')
      ->first();
    $nomina->montos = json_decode($nomina->montos);

    return response()->json(["nomina" => $nomina]);
  }

}
