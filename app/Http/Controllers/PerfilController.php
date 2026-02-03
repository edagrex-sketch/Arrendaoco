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
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,'.$usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'email' => $request->email,
        ]);

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

        // Logic to redirect to create property would go here, for now redirect to profile with message
        return redirect()->route('perfil.index')->with('success', '¡Ahora eres Propietario! Puedes publicar inmuebles.');
    }
}
