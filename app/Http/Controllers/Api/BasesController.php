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


class BasesController extends Controller
{
    /** Salario minimo*/
    public function verActual() {
        $salario_minimo = SalarioMinimo::where('estatus', 'activo')->first();

        $cesta_ticket = CestaTicket::where('estatus', 'activa')->first();

        return response()->json(["salario_minimo" => $salario_minimo, "cesta_ticket" => $cesta_ticket]);
    }

    public function histoCesta() {
      $cesta_ticket = CestaTicket::all();

      return response()->json($cesta_ticket);
    }

    public function histoSalario() {
      $salario = SalarioMinimo::all();

      return response()->json($salario);
    }

    public function updSalario(Request $request) {
      DB::transaction(function () use($request) {
        $salario_anterior = SalarioMinimo::where('estatus', 'activo')->first();
        $salario_anterior->estatus = 'inactivo';
        $salario_anterior->hasta = date("d-m-Y");
        $salario_anterior->save();

        $salario = new SalarioMinimo;
        $salario->monto = $request->monto;
        $salario->estatus = 'activo';
        $salario->tipo = 'mensual';
        $salario->desde = date("d-m-Y");
        $salario->save();

        DB::table('salario')
          ->where('estatus', 'activo')
          ->update([])
      });

      return response()->json(["res" => "Done!"]);
    }

    public function updCesta(Request $request) {
        DB::transaction(function () use($request)  {
          $cesta_anterior = CestaTicket::where('estatus', 'activa')->first();
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
