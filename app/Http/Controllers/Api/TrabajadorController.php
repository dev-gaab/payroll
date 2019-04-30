<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
// models
use App\Models\Trabajador;
use App\Models\Salario;
use App\Models\SalarioMinimo;
use App\Models\BaseLegal;

class TrabajadorController extends Controller
{
  // Funcion de ver todos los trabajadores de una empresa
  public function verTodos($id)
  {
    $trabajadores = Trabajador::where('empresa_id', $id)
      ->where('estatus', 'activo')
      ->orderBy('cedula', 'asc')
      ->get();

    return response()->json(['trabajadores' => $trabajadores]);
  }

  public function ver($id)
  {
    $trabajador = Trabajador::find($id);

    $salario = Salario::where('trabajador_id', $id)
      ->where('estatus', 'activo')
      ->select('id', 'salario')
      ->first();

    $salario_minimo = SalarioMinimo::where('estatus', 'activo')->first();

    if ($salario_minimo->monto == $salario->salario)
      $is_salario_minimo = true;
    else
      $is_salario_minimo = false;

    if ($trabajador != "") {
      return response()->json([
        'trabajador' => $trabajador,
        'salario' => $salario,
        'isSalarioMinimo' => $is_salario_minimo,
        'message' => 'ok'
      ]);
    } else {
      return response()->json(['error' => 'No se encontro']);
    }
  }
  // Recibir el id de la empresa para poder seleccionar el salario minimo
  public function modificar($id, Request $request)
  {
    $trabajador = Trabajador::find($id);

    //  validar cedula
    $validate_cedula = Trabajador::where('cedula', $request->cedula)
      ->where('empresa_id', $trabajador->empresa_id)
      ->where('estatus', 'activo')
      ->where('id', '<>', $id)
      ->first();

    if($validate_cedula != null)
      return response()->json(['error' => 'Ya existe un trabajador con la misma cedula']);

    $sal = Salario::where('trabajador_id', $id)
      ->where('estatus', 'activo')
      ->first();

    if ($request->salario_minimo == true) {

      $salarioMin = SalarioMinimo::where('estatus', 'activo')->first();
      $salarioMen = $salarioMin->monto;
    } else {
      $salarioMen = $request->salario;
    }


    if ($sal->salario !== $salarioMen) {

      date_default_timezone_set('America/Caracas');

      $sal->estatus = 'inactivo';
      $sal->hasta = date('d-m-Y');
      $sal->save();

      $salario_diario = $salarioMen / 30;

      $salario = new Salario();

      $salario->trabajador_id = $id;
      $salario->salario = $salarioMen;
      $salario->salario_diario = $salario_diario;
      $salario->tipo = 'quincenal';
      $salario->desde = date("d-m-Y");
      $salario->estatus = 'activo';
      $salario->save();
    }

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

    if ($trabajador->save()) {
      return response()->json(['message' => 'ok']);
    } else {
      return response()->json(['message' => 'error']);
    }
  }

  public function agregar($id, Request $request)
  {

    // Validar Cedula existente
      $validate_cedula = Trabajador::where('cedula', $request->cedula)
        ->where('empresa_id', $id)
        ->where('estatus', 'activo')
        ->first();

      if($validate_cedula != null)
        return response()->json(['error' => 'Ya existe un trabajador con la misma cedula']);

    DB::transaction(function () use($id, $request) {

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

      $trabajador->save();

      date_default_timezone_set('America/Caracas');

      if ($request->salario_minimo == true) {
        $salarioMin = SalarioMinimo::where('estatus', 'activo')->first();
        $salarioMen = $salarioMin->monto;
      } else {
        $salarioMen = $request->salario;
      }

      $salario_diario = $salarioMen / 30;

      $salario = new Salario();

      $salario->trabajador_id = $trabajador->id;
      $salario->salario = $salarioMen;
      $salario->salario_diario = $salario_diario;
      $salario->tipo = 'quincenal';
      $salario->desde = date("d-m-Y");
      $salario->estatus = 'activo';

      $salario->save();
    });


    return response()->json(['message' => 'ok']);

  }

  public function inhabilitar($id, Request $request)
  {
    $trabajador = Trabajador::find($id);

    $trabajador->estatus = 'inhabilitado';
    $trabajador->fecha_egreso = $request->fecha_egreso;
    $trabajador->save();

    return response()->json(["res" => "done!"]);
  }
}
