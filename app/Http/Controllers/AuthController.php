<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nombre'     => 'required|string',
            'apellido'   => 'required|string',
            'email'    => 'required|string|email|unique:usuario',
            'username'    => 'required|string|unique:usuario',
            'password' => 'required|string|confirmed',
            'rol'   => 'required|string'
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json(['validateErrors' => $errors]);
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

        if($user->rol == 'admin') {
            $isAdmin = true;
        }

        return response()->json([
            'isAdmin' => $isAdmin,
            'user' => [
                'token' => $tokenResult->accessToken,
                'name' => $user->nombre.' '.$user->apellido,
                'email' => $user->email
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' =>
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
