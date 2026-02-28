<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    /**
     * Actualizar perfil
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'foto_perfil' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_perfil')) {
            // Eliminar foto antigua si existe
            if ($usuario->foto_perfil) {
                Storage::delete('public/' . $usuario->foto_perfil);
            }
            $usuario->foto_perfil = $request->file('foto_perfil')->store('perfil', 'public');
        }

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->fill($request->only(['nombre', 'email']));
        $usuario->save();

        return new UserResource($usuario->load('roles'));
    }

    /**
     * Solicitar convertirse en propietario
     */
    public function publicar(Request $request)
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        if (!$usuario->tieneRol('propietario')) {
            $usuario->asignarRol('propietario');
            return response()->json([
                'success' => true,
                'message' => '¡Felicidades! Ahora ya puedes publicar tus inmuebles.',
                'user' => new UserResource($usuario->load('roles'))
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ya eres propietario.'
        ]);
    }
}
