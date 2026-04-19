<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\Usuario;
use App\Events\NotificationSent;

class NotificationService
{
    /**
     * Envía una notificación a un usuario específico.
     */
    public static function send($usuarioId, $titulo, $mensaje, $tipo = 'sistema', $referenciaId = null)
    {
        $notificacion = Notificacion::create([
            'usuario_id'    => $usuarioId,
            'titulo'        => $titulo,
            'mensaje'       => $mensaje,
            'tipo'          => $tipo,
            'referencia_id' => $referenciaId,
            'leida'         => false
        ]);

        // Disparar evento de tiempo real
        event(new NotificationSent($notificacion));

        return $notificacion;
    }

    /**
     * Envía una notificación a todos los administradores.
     */
    public static function notifyAdmins($titulo, $mensaje, $tipo = 'sistema', $referenciaId = null)
    {
        $admins = Usuario::whereHas('roles', function($q) {
            $q->where('nombre', 'admin');
        })->orWhere('es_admin', true)->get();

        foreach ($admins as $admin) {
            self::send($admin->id, $titulo, $mensaje, $tipo, $referenciaId);
        }
    }
}
