<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
// models
use App\Models\Empresa;


class ReportesController extends Controller
{
    public function empresasActivas() {
    	$empresas_activas = Empresa::where('estatus', 'activa')->get();
    	
    	return response()->json($empresas_activas);
    }

    public function empresasInactivas(){
    	$empresas_inactivas = Empresa::where('estatus', '<>' ,'activa')->get();

    	return response()->json($empresas_inactivas);
    }

    public function nominaUno($id) {
    	$nomina = DB::table('nomina_detalle')
    		->join('nomina', 'nomina_detalle.nomina_id', 'nomina.id')
    		->join('trabajador', 'nomina_detalle.trabajador_id', 'trabajador.id')
    		->where('nomina_detalle.id', $id)
    		->first();

    	$nomina->montos = json_decode($nomina->montos);
        $nomina->montos->ivss = round($nomina->montos->ivss, 2);
        $nomina->montos->pago_salario = round($nomina->montos->pago_salario, 2);
        $nomina->montos->total_asignaciones = round($nomina->montos->total_asignaciones, 2);
        $nomina->montos->total_deducciones = round($nomina->montos->total_deducciones, 2);
        $nomina->montos->monto_total = round($nomina->montos->monto_total, 2);

    	return response()->json($nomina);
    }
}