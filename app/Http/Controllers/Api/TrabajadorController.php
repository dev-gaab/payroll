<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trabajador;
use App\Models\Salario;

class TrabajadorController extends Controller
{
    // Funcion de ver todos los trabajadores de una empresa
    public function verTodos($id)
    {
        $trabajadores = Trabajador::where('empresa_id', $id)
            ->orderBy('cedula', 'asc')
            ->orderBy('estatus', 'asc')
            ->get();

        return response()->json(['trabajadores' => $trabajadores]);
    }

    public function ver($id)
    {
        $trabajador = Trabajador::find($id);
        
        $salario = Salario::where('trabajador_id', $id)
            ->where('estatus', 'activo')
            ->get();

        if ($trabajador != "") {
            return response()->json(['trabajador' => $trabajador, 'message' => 'ok']);
        } else {
            return response()->json(['message' => 'no']);
        }
        
    }

    public function modificar($id, Request $request)
    {
        $trabajador = Trabajador::find($id);

        $trabajador->cedula = $request->cedula;
        $trabajador->nombre1 = $request->nombre1;
        $trabajador->nombre2 = $request->nombre2;
        $trabajador->apellido1 = $request->apellido1;
        $trabajador->apellido2 = $request->apellido2;
        $trabajador->cargo = $request->cargo;
        $trabajador->fecha_nacimiento = $request->fecha_nacimiento;
        $trabajador->sexo = $request->sexo;
        $trabajador->direccion = $request->direccion;
        $trabajador->telefono_fijo = $request->telefono_fijo;
        $trabajador->telefono_celular = $request->telefono_celular;
        $trabajador->fecha_ingreso = $request->fecha_ingreso;
        $trabajador->fecha_egreso = $request->fecha_egreso;
        
        if ($trabajador->save()){
            return response()->json(['message' => 'ok']);
        } else {
            return response()->json(['message' => 'error']);
        }
    }

    public function agregar($id, Request $request)
    {
        $trabajador = new Trabajador();

        $trabajador->empresa_id = $id;
        $trabajador->cedula = $request->cedula;
        $trabajador->nombre1 = $request->nombre1;
        $trabajador->nombre2 = $request->nombre2;
        $trabajador->apellido1 = $request->apellido1;
        $trabajador->apellido2 = $request->apellido2;
        $trabajador->cargo = $request->cargo;
        $trabajador->fecha_nacimiento = $request->fecha_nacimiento;
        $trabajador->sexo = $request->sexo;
        $trabajador->direccion = $request->direccion;
        $trabajador->telefono_fijo = $request->telefono_fijo;
        $trabajador->telefono_celular = $request->telefono_celular;
        $trabajador->fecha_ingreso = $request->fecha_ingreso;
        $trabajador->fecha_egreso = $request->fecha_egreso;
        $trabajador->estatus = 'activo';
    }
}
