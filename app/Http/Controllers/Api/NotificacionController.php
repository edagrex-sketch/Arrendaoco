<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Listar notificaciones del usuario autenticado
     */
    public function index(Request $request)
    {
        $notificaciones = Notificacion::where('usuario_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($notificaciones);
    }

    /**
     * Marcar una notificación como leída
     */
    public function update(Request $request, Notificacion $notificacion)
    {
        if ($notificacion->usuario_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $notificacion->update(['leida' => true]);

        return response()->json($notificacion);
    }

    /**
     * Marcar todas como leídas
     */
    public function markAllAsRead(Request $request)
    {
        Notificacion::where('usuario_id', $request->user()->id)
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar una notificación
     */
    public function destroy(Request $request, Notificacion $notificacion)
    {
        if ($notificacion->usuario_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $notificacion->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Contar no leídas
     */
    public function unreadCount(Request $request)
    {
        $count = Notificacion::where('usuario_id', $request->user()->id)
            ->where('leida', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }
}
