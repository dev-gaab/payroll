<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trabajador;
use App\Models\Salario;
use App\Models\SalarioMinimo;

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
            return response()->json(['error' => 'No se encontro']);
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

        if ($trabajador->save()){
            date_default_timezone_set('America/Caracas');

            if ($request->salario_minimo == true){
                $salarioMin = SalarioMinimo::where('estatus', 'activo')->first();
                $salarioMen = $salarioMin->monto;
            } else {
                $salarioMen = $request->salario;
            }

            $salario_diario = $salarioMen/30;

            $salario = new Salario();
        
            $salario->trabajador_id = $trabajador->id;
            $salario->salario = $salarioMen;
            $salario->salario_diario = $salario_diario;
            $salario->tipo = $request->tipo_salario;
            $salario->desde = date("d-m-Y");
            $salario->estatus = 'activo';

            if ($salario->save()){
                return response()->json(['message' => 'ok']);
            } else {
                return response()->json(['error' => 'salario']);
            }
        } else {
            return response()->json(['error' => 'trabajador']);
        }
    }
}
