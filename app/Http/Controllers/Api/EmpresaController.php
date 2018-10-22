<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    public function verTodas()
    {
        $empresas = Empresa::all();

        return response()->json(["empresas" => $empresas]);
    }

    public function ver($id)
    {
        $empresa =  Empresa::find($id);

        return response()->json(["empresa" => $empresa]);
    }

    public function modificar($id, Request $request)
    {
        $empresa = Empresa::find($id);
        $empresa->rif = $request->rif;
        $empresa->razon_social = $request->razon_social;
        $empresa->direccion = $request->direccion;
        $empresa->num_afiliacion_ivss = $request->num_afiliacion_ivss;
        $empresa->fecha_inscripcion_ivss = $request->fecha_inscripcion_ivss;
        
        if ($empresa->save()){
            return response()->json(['res' => "Modificado"]);
        }

    }

    public function agregar (Request $request)
    {
        $empresa = new Empresa();
        $empresa->rif = $request->rif;
        $empresa->razon_social = $request->razon_social;
        $empresa->direccion = $request->direccion;
        $empresa->num_afiliacion_ivss = $request->num_afiliacion_ivss;
        $empresa->fecha_inscripcion_ivss = $request->fecha_inscripcion_ivss;
        $empresa->riesgo_ivss = $request->riesgo_ivss;
        $empresa->num_afiliacion_faov = $request->num_afiliacion_faov;
        $empresa->num_afiliacion_inces = $request->num_afiliacion_inces;
        $empresa->estatus = 'habilitada';

        if ($empresa->save()){
            return response()->json(['message' => "Registrado"]);
        } else {
            return response()->json(['error' => "error en el registro"]);
        }
    }
}
