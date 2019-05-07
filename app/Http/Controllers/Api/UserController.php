<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

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
}
