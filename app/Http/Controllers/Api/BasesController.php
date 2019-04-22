<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseLegal;
use App\Models\SalarioMinimo;
use App\Models\Asignaciones;
use App\Models\Deducciones;
use App\Models\CestaTicket;

class BasesController extends Controller
{
    /** Salario minimo*/
    public function verTodosSalario() {
        $salarios = SalarioMinimo::all();

        return response()->json($salarios);
    }

    public function verSalario($id) {
        $salario = SalarioMinimo::find($id);

        return response()->json($id);
    }

    public function addSalario(Request $request) {
        $salario_anterior = SalarioMinimo::where('estatus', 'activo')->first();
        $salario_anterior->estatus = 'inactivo';
        $salario_anterior->hasta = date("d-m-Y");
        $salario_anterior->save();

        $salario = new SalarioMinimo;
        $salario->monto = $request->monto;
        $salario->estatus = 'activo',
        $salario->desde = date("d-m-Y");
        $salario->save();

        return response()->json(["res" => "Done!"]);
    }

    /** Cesta ticket */

    public function verTodosCesta() {
        $cestas = CestaTicket::all();

        return response()->json($cestas);
    }

    public function verCesta($id) {
        $cesta = CestaTicket::find($id);

        return response()->json($cesta);
    }

    public function addCesta(Request $request) {
        $cesta_anterior = CestaTicket::where('estatus', 'activa')->first();
        $cesta_anterior->estatus = 'inactiva';
        $cesta_anterior->hasta = date("d-m-Y");
        $cesta_anterior->save();

        $cesta = new CestaTicket;
        $cesta->monto = $request->monto;
        $cesta->desde = $request->desde;
        $cesta->estatus = 'activa';

        $cesta->save();

        return response()->json(["res" => "Done!"]);
    }
}
