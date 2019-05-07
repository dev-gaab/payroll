<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// Models
use App\Models\Empresa;
use App\Models\UsuarioEmpresa;
use App\Models\Historial;
use App\Models\Sesion;

/**
 * Class EmpresaController
 * @package App\Http\Controllers\Api
 */
class EmpresaController extends Controller
{
  public function verTodas(Request $request)
  {

    $empresas = Empresa::where('estatus', 'activa')
      ->orderBy('estatus', 'desc')
      ->get();

    return response()->json(["empresas" => $empresas]);
  }

  public function ver(Request $request, $id)
  {

    $empresa =  Empresa::find($id);

    return response()->json(["empresa" => $empresa]);
  }

  public function modificar($id, Request $request)
  {

    $empresa = Empresa::find($id);

    $validate_rif = Empresa::where('rif', $request->rif)
      ->where('id', '<>', $id)
      ->first();

    if ($validate_rif !== null)
      return response()->json(["error" => $validate_rif]);

    $empresa->rif = $request->rif;
    $empresa->razon_social = $request->razon_social;
    $empresa->direccion = $request->direccion;
    $empresa->num_afiliacion_ivss = $request->num_afiliacion_ivss;
    $empresa->fecha_inscripcion_ivss = $request->fecha_inscripcion_ivss;
    $empresa->riesgo_ivss = $request->riesgo_ivss;
    $empresa->num_afiliacion_faov = $request->num_afiliacion_faov;
    $empresa->num_afiliacion_inces = $request->num_afiliacion_inces;

    $empresa->save();

    $sesion = Sesion::where("final", null)->where("usuario_id", Auth::id())->first();

    $datos_sesion = [
      "accion" => "Modificar empresa",
      "fecha" => date('Y-m-d h:i:s A')
    ]; 

    $historial = new Historial();
    $historial->sesion_id = $sesion->id;
    $historial->data  = json_encode($datos_sesion);
    $historial->save();

    return response()->json(['res' => 'Empresa Modificada']);
  }

  public function agregar(Request $request)
  {
    $validate_rif = Empresa::where('rif', $request->rif)
      ->where('estatus', 'activa')
      ->first();

    if($validate_rif != null)
      return response()->json(["error" => 'Rif Existente']);

    $empresa = new Empresa();
    $empresa->rif = $request->rif;
    $empresa->razon_social = $request->razon_social;
    $empresa->direccion = $request->direccion;
    $empresa->num_afiliacion_ivss = $request->num_afiliacion_ivss;
    $empresa->fecha_inscripcion_ivss = $request->fecha_inscripcion_ivss;
    $empresa->riesgo_ivss = $request->riesgo_ivss;
    $empresa->num_afiliacion_faov = $request->num_afiliacion_faov;
    $empresa->num_afiliacion_inces = $request->num_afiliacion_inces;
    $empresa->estatus = 'activa';

    $empresa->save();

    $sesion = Sesion::where("final", null)->where("usuario_id", Auth::id())->first();

    $datos_sesion = [
      "accion" => "Agregar empresa",
      "fecha" => date('Y-m-d h:i:s A')
    ]; 

    $historial = new Historial();
    $historial->sesion_id = $sesion->id;
    $historial->data  = json_encode($datos_sesion);
    $historial->save();

    return response()->json(['res' => "Done!"]);


  }

  //    Funcion para deshabilitar una empresa
  public function deshabilitar($id)
  {
    $empresa = Empresa::find($id);

    $empresa->estatus = 'inactiva';
    $empresa->save();

    $sesion = Sesion::where("final", null)->where("usuario_id", Auth::id())->first();

    $datos_sesion = [
      "accion" => "Deshabilitar empresa",
      "fecha" => date('Y-m-d h:i:s A')
    ]; 

    $historial = new Historial();
    $historial->sesion_id = $sesion->id;
    $historial->data  = json_encode($datos_sesion);
    $historial->save();


    return response()->json(["res" => "Empresa deshabilitada"]);
  }

  // Funcion para relacionar empresa y usuarios
  public function createEmpresaUsuario($empresa_id, $usuario_id)
  {
    // Relacionar empresa y usuario
    $empresa_usuario = new UsuarioEmpresa();
    $empresa_usuario->empresa_id = $empresa_id;
    $empresa_usuario->usuario_id = $usuario_id;
    $empresa_usuario->estatus = 'activo';

    if ($empresa_usuario->save()) {
      return true;
    } else {
      return false;
    }
  }

  public function empresaUsuario($empresa_id, $usuario_id)
  {

    $empresa_usuario = UsuarioEmpresa::where('empresa_id', '=', $empresa_id)
      ->where('usuario_id', '=', $usuario_id)
      ->first();

    if (!$empresa_usuario) {
      return false;
    }

    return true;
  }
}
