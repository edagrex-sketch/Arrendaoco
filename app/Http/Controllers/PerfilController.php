<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Usuario;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        return view('perfil.index', compact('usuario'));
    }

    public function update(Request $request)
    {
        $usuario = Auth::user();
        /** @var Usuario $usuario */

        $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
            'foto_perfil' => 'nullable|image|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('foto_perfil')) {
            // Eliminar foto anterior si existe
            if ($usuario->foto_perfil) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($usuario->foto_perfil);
            }

            $path = $request->file('foto_perfil')->store('perfil', 'public');
            \App\Support\MediaUrl::ensurePublicStorageCopy($path);
            $usuario->update(['foto_perfil' => $path]);
        }

        if ($request->filled('password')) {
            $usuario->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado exitosamente');
    }

    public function publicar()
    {
        $usuario = Auth::user();
        /** @var Usuario $usuario */
        
        if (!$usuario->tieneRol('propietario')) {
            $usuario->asignarRol('propietario');
        }

        // Redireccionar directamente al formulario de creación para una mejor experiencia de usuario
        return redirect()->route('inmuebles.create')->with('success', '¡Felicidades! Ahora eres Propietario. Ya puedes publicar tu primer inmueble.');
    }
}
