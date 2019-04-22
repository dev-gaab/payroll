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
    // ver todas las bases legales de una empresa.
    public function verTodas($id)
    {
        $bases =  BaseLegal::where('empresa_id', $id)
            ->orderBy('estatus', 'asc')
            ->get();
        
        return response()->json(['bases' => $bases]);
    }

    // ver solo una base legal activa.
    public function verMod($id)
    {
        $base =  BaseLegal::where('empresa_id', $id)
            ->where('estatus', 'activa')
            ->first();
        
        $sm = SalarioMinimo::find($base->salario_minimo_id);
        $as = Asignaciones::find($base->asignaciones_id);
        $ded = Deducciones::find($base->deducciones_id);
        $ct = CestaTicket::find($base->cesta_ticket_id);

        $ba = [
            'id' => $base->id,
            'salario_minimo' => $sm->monto,
            'unidad_tributaria' => $ct->unidad_tributaria,
            'cantidad' => $ct->cantidad,
            'he_diurnas' => $as->he_diurnas,
            'he_nocturnas' => $as->he_nocturnas,
            'feriados' => $as->feriados,
            'ivss' => $ded->ivss,
            'faov' => $ded->faov,
            'paro_forzoso' => $ded->paro_forzoso
        ];
        
        return response()->json(['base' => $ba]);
    }

    public function ver($id)
    {
        $base =  BaseLegal::find($id);
        
        $sm = SalarioMinimo::find($base->salario_minimo_id);
        $as = Asignaciones::find($base->asignaciones_id);
        $ded = Deducciones::find($base->deducciones_id);
        $ct = CestaTicket::find($base->cesta_ticket_id);

        $ba = [
            'id' => $base->id,
            'salario_minimo' => $sm->monto,
            'unidad_tributaria' => $ct->unidad_tributaria,
            'cantidad' => $ct->cantidad,
            'he_diurnas' => $as->he_diurnas,
            'he_nocturnas' => $as->he_nocturnas,
            'feriados' => $as->feriados,
            'ivss' => $ded->ivss,
            'faov' => $ded->faov,
            'paro_forzoso' => $ded->paro_forzoso
        ];
        
        return response()->json(['base' => $ba]);
    }

    public function registrar ($id, Request $request)
    {

        date_default_timezone_set('America/Caracas');

        $sm = new SalarioMinimo();
        $as = new Asignaciones();
        $dd = new Deducciones();
        $ct = new CestaTicket();
        $base = new BaseLegal();

        // Datos de salario minimo
        $sm->monto = $request->salario_minimo;
        $sm->tipo = 'Mensual';
        $sm->desde = date('d-m-Y');
        $sm->estatus = 'activo';
        $sm->save();
        // Datos de asignaciones
        $as->he_diurnas = $request->he_diurnas;
        $as->he_nocturnas = $request->he_nocturnas;
        $as->feriados = $request->feriados;
        $as->desde = date('d-m-Y');
        $as->estatus = 'activa';
        $as->save();
        // Datos de deducciones
        $dd->ivss = $request->ivss;
        $dd->faov = $request->faov;
        $dd->paro_forzoso = $request->paro_forzoso;
        $dd->desde = date('d-m-Y');
        $dd->estatus = 'activa';
        $dd->save();
        // Datos de Cestaticket
        $ct->unidad_tributaria = $request->unidad_tributaria;
        $ct->cantidad = $request->cantidad;
        $ct->desde = date('d-m-Y');
        $ct->estatus = 'activa';
        $ct->save();
        // Datos de base
        $base->empresa_id = $id;
        $base->salario_minimo_id = $sm->id;
        $base->asignaciones_id = $as->id;
        $base->deducciones_id = $dd->id;
        $base->cesta_ticket_id = $ct->id;
        $base->desde = date('d-m-Y');
        $base->estatus = 'activa';

        if ($base->save()){
            return response()->json(['message' => 'registrada']);
        } else {
            return response()->json(['error' => 'error']);
        }

    }

    public function modificar ($id, Request $request)
    {
        $base1 =  BaseLegal::where('empresa_id', $id)
            ->where('estatus', 'activa')
            ->first();
        
        $sm1 = SalarioMinimo::find($base1->salario_minimo_id);
        $as1 = Asignaciones::find($base1->asignaciones_id);
        $ded1 = Deducciones::find($base1->deducciones_id);
        $ct1 = CestaTicket::find($base1->cesta_ticket_id);

        if ($sm1->monto != $request->salario_minimo) {
            
            $sm = new SalarioMinimo();
             // Datos de salario minimo
            $sm->monto = $request->salario_minimo;
            $sm->tipo = 'Mensual';
            $sm->desde = date('d-m-Y');
            $sm->estatus = 'activo';
            $sm->save();
            
            $sm1->estatus = 'inactivo';
            $sm1->save();

            $sm_id = $sm->id;
        } else {
            $sm_id = $sm1->id;
        }

        if ($as1->he_diurnas == $request->he_diurnas && $as1->he_nocturnas == $request->he_nocturnas && $as1->feriados == $request->feriados) {
            
            $as_id = $as1->id;
    
        } else {
            $as = new Asignaciones();
            // Datos de asignaciones
            $as->he_diurnas = $request->he_diurnas;
            $as->he_nocturnas = $request->he_nocturnas;
            $as->feriados = $request->feriados;
            $as->desde = date('d-m-Y');
            $as->estatus = 'activa';
            $as->save();

            $as1->estatus = 'inactiva';
            $as1->save();

            $as_id = $as->id;
        }

        if ($ded1->ivss == $request->ivss && $ded1->faov == $request->faov && $ded1->paro_forzoso == $request->paro_forzoso) {
            
            $ded_id = $ded1->id;
    
        } else {
            $dd = new Deducciones();
            // Datos de deducciones
            $dd->ivss = $request->ivss;
            $dd->faov = $request->faov;
            $dd->paro_forzoso = $request->paro_forzoso;
            $dd->desde = date('d-m-Y');
            $dd->estatus = 'activa';
            $dd->save();

            $ded1->estatus = 'inactiva';
            $ded1->save();

            $ded_id = $dd->id;
        }

        if ($ct1->unidad_tributaria == $request->unidad_tributaria && $ct1->cantidad == $request->cantidad) {
            
            $ct_id = $ct1->id;
    
        } else {
            $ct = new CestaTicket();
            // Datos de Cestaticket
            $ct->unidad_tributaria = $request->unidad_tributaria;
            $ct->cantidad = $request->cantidad;
            $ct->desde = date('d-m-Y');
            $ct->estatus = 'activa';
            $ct->save();

            $ct1->estatus = 'inactiva';
            $ct1->save();

            $ct_id = $ct->id;
        }

        if ($sm_id == $sm1->id && $as_id == $as1->id && $ded_id == $ded1->id && $ct_id == $ct1->id){
            return response()->json(['message' => 'ok']);

        } else {
            
            $base = new BaseLegal();
             // Datos de base
            $base->empresa_id = $id;
            $base->salario_minimo_id = $sm_id;
            $base->asignaciones_id = $as_id;
            $base->deducciones_id = $ded_id;
            $base->cesta_ticket_id = $ct_id;
            $base->desde = date('d-m-Y');
            $base->estatus = 'activa';

            if ($base->save()){
                $base1->hasta = date('d-m-Y');
                $base1->estatus = 'inactiva';
                $base1->save();

                return response()->json(['message' => 'modificada']);
            } else {
                return response()->json(['error' => 'error']);
            }
        }
    }
}
