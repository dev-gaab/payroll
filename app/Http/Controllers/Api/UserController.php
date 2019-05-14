<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Models\Historial;
use App\Models\Sesion;

class UserController extends Controller
{
  public function all() {
    $users = User::all();

    return response()->json($users);
  }

  public function find($id) {
    $user = User::find($id);

    return response()->json($user);
  }

  public function disable($id) {
    $user = User::find($id);

    $user->estatus = 'inhabilitado';
    $user->save();

    return response()->json(['msg' => 'done!']);
  }

  public function enable($id) {
    $user = User::find($id);

    $user->estatus = 'activo';
    $user->save();

    return response()->json(['msg' => 'done!']);
  }

  public function update ($id, Request $request) {
    // validando
    $correo_existente = User::where('email', $request->email)
      ->where('id', '<>', $id)
      ->first();

    if($correo_existente) {
      return response()->json(['error' => "Correo existente"]);
    }

    $user = User::find($id);
    $user->nombre = $request->nombre;
    $user->apellido = $request->apellido;
    $user->email = $request->email;
    if($request->isChangingPassword) {
      $user->password = bcrypt($request->password);
    }

    $user->save();

    return response()->json(['msg' => 'done!']);
  }

  // Historial de usuario
  public function verHistorial($id) {
    $historial = Historial::where('data->user', $id)->get();

    for ($i = 0; $i < sizeof($historial); $i++) {
      $historial[$i]->data = json_decode($historial[$i]->data);
    }

    return response()->json($historial);
  }

  public function guardarProceso(Request $request) {
    $sesion = Sesion::where("final", null)->where("usuario_id", Auth::id())->first();

    $proceso = new Historial();

    $proceso->sesion_id = $sesion->id;
    $proceso->data = json_encode([
      "proceso" => $request->proceso,
      "fecha" => date('Y-m-d h:i:s'),
      "user" => Auth::id() 
    ]);

    $proceso->save();

    return response()->json(["res" => "Done!"]);
  }
}
