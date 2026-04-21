<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\Usuario;
use App\Events\NotificationSent;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

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

        // Disparar evento de tiempo real (Web)
        event(new NotificationSent($notificacion));

        // Enviar Notificación Push (Móvil)
        self::sendPushNotification($notificacion);

        return $notificacion;
    }

    /**
     * Envía la notificación vía Firebase Cloud Messaging (FCM V1).
     */
    protected static function sendPushNotification(Notificacion $notificacion)
    {
        $usuario = $notificacion->usuario;
        
        if (!$usuario || !$usuario->fcm_token) {
            return;
        }

        try {
            $accessToken = self::getAccessToken();
            if (!$accessToken) return;

            $configJson = env('FCM_SERVICE_ACCOUNT_JSON');
            if (!$configJson) return;
            $config = json_decode($configJson, true);
            $projectId = $config['project_id'] ?? 'arrendaoco-fad79';

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $client = new Client();
            $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $usuario->fcm_token,
                        'notification' => [
                            'title' => $notificacion->titulo,
                            'body'  => $notificacion->mensaje,
                        ],
                        'data' => [
                            'id' => (string) $notificacion->id,
                            'tipo' => $notificacion->tipo,
                            'referencia_id' => (string) $notificacion->referencia_id,
                            'chat_id' => $notificacion->tipo === 'mensaje' ? (string) $notificacion->referencia_id : '',
                            'sender_id' => $notificacion->tipo === 'mensaje' ? (string) \Illuminate\Support\Facades\Auth::id() : '',
                        ],
                        'android' => [
                            'priority' => 'high',
                            'notification' => [
                                'sound' => 'default',
                                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                                'tag' => $notificacion->tipo === 'mensaje' ? 'chat_' . $notificacion->referencia_id : 'notif_' . $notificacion->id,
                            ],
                        ],
                    ],
                ],
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("❌ Error enviando Push FCM V1: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Genera un Access Token para Google API usando el Service Account JSON del .env.
     */
    private static function getAccessToken()
    {
        $configJson = env('FCM_SERVICE_ACCOUNT_JSON');
        if (!$configJson) {
            Log::error("⚠️ FCM_SERVICE_ACCOUNT_JSON no configurado en .env.");
            return null;
        }

        $config = json_decode($configJson, true);
        if (!$config) {
            Log::error("❌ Formato de FCM_SERVICE_ACCOUNT_JSON inválido en .env.");
            return null;
        }

        $now = time();
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'iss'   => $config['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = '';
        openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $config['private_key'], 'SHA256');
        $base64UrlSignature = self::base64UrlEncode($signature);

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        try {
            $client = new Client();
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion'  => $jwt,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['access_token'];
        } catch (\Exception $e) {
            Log::error("❌ Error obteniendo Access Token: " . $e->getMessage());
            return null;
        }
    }

    private static function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Envía una notificación a todos los administradores.
     */
    public static function notifyAdmins($titulo, $mensaje, $tipo = 'sistema', $referenciaId = null)
    {
        $admins = \App\Models\Usuario::whereHas('roles', function($q) {
            $q->where('nombre', 'admin');
        })->orWhere('es_admin', true)->get();

        foreach ($admins as $admin) {
            self::send($admin->id, $titulo, $mensaje, $tipo, $referenciaId);
        }
    }
}
