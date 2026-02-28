<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($data)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $usuario->load('roles');

        $token = $usuario->createToken('arrendaoco-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'usuario' => new UserResource($usuario),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada'
        ]);
    }
}
