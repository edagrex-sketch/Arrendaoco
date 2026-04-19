<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Devuelve la vista parcial con la lista de notificaciones recientes.
     */
    public function index()
    {
        $notificaciones = Notificacion::where('usuario_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return view('partials.notifications_list', compact('notificaciones'))->render();
    }

    /**
     * Devuelve el conteo de notificaciones no leídas.
     */
    public function unreadCount()
    {
        $count = Notificacion::where('usuario_id', Auth::id())
            ->where('leida', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Marca una notificación específica como leída.
     */
    public function markAsRead(Notificacion $notificacion)
    {
        if ($notificacion->usuario_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notificacion->update(['leida' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Marca todas las notificaciones del usuario como leídas.
     */
    public function markAllAsRead()
    {
        Notificacion::where('usuario_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json(['success' => true]);
    }
}
