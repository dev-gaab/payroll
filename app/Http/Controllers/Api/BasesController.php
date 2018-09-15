<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseLegal;
use App\Models\SalarioMinimo;
use App\Models\Asignaciones;
use App\Models\Deducciones;
use App\Models\CestaTicket;
use Illuminate\Support\Facades\DB;
use App\Models\Salario;

class BasesController extends Controller
{
  /** Salario minimo*/
  public function verActual()
  {
    $salario_minimo = SalarioMinimo::where('estatus', 'activo')->first();

    $cesta_ticket = CestaTicket::where('estatus', 'activa')->first();

    return response()->json(["salario_minimo" => $salario_minimo, "cesta_ticket" => $cesta_ticket]);
  }

  public function histoCesta()
  {
    $cesta_ticket = CestaTicket::all();

    return response()->json($cesta_ticket);
  }

  public function histoSalario()
  {
    $salario = SalarioMinimo::all();

    return response()->json($salario);
  }

  public function updSalario(Request $request)
  {
    $salario_anterior = SalarioMinimo::where('estatus', 'activo')->first();
    if ($salario_anterior->monto == $request->monto) return response()->json(["error" => "No hubo modificaciones en el monto"]);

    DB::transaction(function () use ($request, $salario_anterior) {
      $salario_anterior->estatus = 'inactivo';
      $salario_anterior->hasta = date("d-m-Y");
      $salario_anterior->save();

      $salario = new SalarioMinimo;
      $salario->monto = $request->monto;
      $salario->estatus = 'activo';
      $salario->tipo = 'mensual';
      $salario->desde = date("d-m-Y");
      $salario->save();

      $salarios_ant = Salario::where('salario', $salario_anterior->monto)->where('estatus', 'activo')->get();

      foreach ($salarios_ant as $sal) {
        $sal_nuevo = new Salario();
        $sal_nuevo->trabajador_id = $sal->trabajador_id;
        $sal_nuevo->salario = $request->monto;
        $sal_nuevo->salario_diario = round($request->monto / 30, 2);
        $sal_nuevo->tipo = $sal->tipo;
        $sal_nuevo->desde = date('Y-m-d');
        $sal_nuevo->estatus = "activo";

        $sal_nuevo->save();

        $upd = Salario::find($sal->id);
        $upd->estatus = "inactivo";
        $upd->hasta = date("Y-m-d");
        $upd->save();
      }
    });

    return response()->json(["res" => "Done!"]);
  }

  public function updCesta(Request $request)
  {
    $cesta_anterior = CestaTicket::where('estatus', 'activa')->first();

    if ($cesta_anterior->cantidad == $request->monto) return response()->json(["error" => "No hubo modificaciones en el monto"]);

    DB::transaction(function () use ($request, $cesta_anterior) {
      $cesta_anterior->estatus = 'inactiva';
      $cesta_anterior->hasta = date("d-m-Y");
      $cesta_anterior->save();

      $cesta = new CestaTicket;
      $cesta->cantidad = $request->monto;
      $cesta->desde = date("d-m-Y");
      $cesta->estatus = 'activa';

      $cesta->save();
    });

    return response()->json(["res" => "Done!"]);
  }
}
