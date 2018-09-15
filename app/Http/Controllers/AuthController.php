<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Models\Sesion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validEmail = User::where('email', $request->email)->first();
        if($validEmail != null) {
            return response()->json(["error" => "Email en uso"]);
        }

        $validEmail = User::where('username', $request->username)->first();
        if($validEmail != null) {
            return response()->json(["error" => "Nombre de usuario en uso"]);
        }

        $user = new User([
            'nombre'     => $request->nombre,
            'apellido'  => $request->apellido,
            'username'  => $request->username,
            'rol'       => $request->rol,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'estatus'   => 'activo'
        ]);

        $user->save();

        return response()->json([
            'res' => 'Successfully created user!'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username'       => 'required|string',
            'password'    => 'required|string'
        ]);

        $credentials = request(['username', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized'], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addDays(1);
        }
        $token->save();

        $isAdmin = false;
        if($user->rol == 'admin') {
            $isAdmin = true;
        }

        $sesiones = new Sesion();
        $sesiones->usuario_id = $user->id;
        $sesiones->inicio = date('Y-m-d h:i:s A');
        $sesiones->save();

        return response()->json([
            'isAdmin' => $isAdmin,
            'user' => [
                'token' => $tokenResult->accessToken,
                'name' => $user->nombre,
                'email' => $user->email,
                'username' => $user->username
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $user_id = Auth::id();

        $sesion = Sesion::where("final", null)
            ->where("usuario_id", $user_id)
            ->first();

        $sesion->final = date('Y-m-d h:i:s');
        
        $sesion->save();

        $request->user()->token()->revoke();

        return response()->json(['message' =>
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
