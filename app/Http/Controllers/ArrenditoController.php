<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArrenditoSetting;
use Illuminate\Support\Facades\Auth;

class ArrenditoController extends Controller
{
    /**
     * Actualiza o crea el nombre de Arrendito para el usuario autenticado
     */
    public function updateName(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:20',
        ]);

        if (Auth::check()) {
            $setting = ArrenditoSetting::updateOrCreate(
                ['usuario_id' => Auth::id()],
                ['nombre' => $request->nombre]
            );

            return response()->json([
                'success' => true,
                'nombre' => $setting->nombre,
                'message' => 'Nombre guardado en la base de datos'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Usuario no autenticado'
        ], 401);
    }

    /**
     * Obtiene el nombre guardado para el usuario autenticado
     */
    public function getName()
    {
        if (Auth::check()) {
            $setting = ArrenditoSetting::where('usuario_id', Auth::id())->first();
            return response()->json([
                'nombre' => $setting ? $setting->nombre : 'Arrendito'
            ]);
        }

        return response()->json(['nombre' => 'Arrendito']);
    }
}
