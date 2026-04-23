<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class FirestoreService
{
    /**
     * Sincroniza un mensaje de chat con Firebase Firestore.
     */
    public static function syncMessage($chatId, $senderId, $texto, $referenciaId = null)
    {
        try {
            $config = self::getFirebaseConfig();
            if (!$config) return false;

            $accessToken = self::getAccessToken($config);
            if (!$accessToken) return false;

            $projectId = $config['project_id'];

            // Estructura para Firestore (Rest API)
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/chats/{$chatId}/mensajes";

            $client = new Client();
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'fields' => [
                        'sender_id' => ['stringValue' => (string)$senderId],
                        'text' => ['stringValue' => $texto],
                        'created_at' => ['timestampValue' => now()->toRfc3339String()],
                        'leido' => ['booleanValue' => false]
                    ]
                ],
            ]);
            
            Log::info("✅ Firestore Message Synced: " . $chatId);

            // Actualizar el puntero del último mensaje
            $parentUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/chats/{$chatId}?updateMask.fieldPaths=last_message&updateMask.fieldPaths=last_message_at&updateMask.fieldPaths=updated_at";
            $client->patch($parentUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'fields' => [
                        'last_message' => ['stringValue' => $texto],
                        'last_message_at' => ['timestampValue' => now()->toRfc3339String()],
                        'updated_at' => ['timestampValue' => now()->toRfc3339String()],
                    ]
                ],
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("❌ Error sincronizando con Firestore: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sincroniza la metadata de un chat
     */
    public static function syncChat($chat)
    {
        try {
            $config = self::getFirebaseConfig();
            if (!$config) return false;

            $accessToken = self::getAccessToken($config);
            if (!$accessToken) return false;

            $projectId = $config['project_id'];
            $chatId = $chat->getFirebaseId();

            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/chats/{$chatId}";

            $client = new Client();
            $client->patch($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'fields' => [
                        'usuario_1' => ['stringValue' => (string)$chat->usuario_1],
                        'usuario_2' => ['stringValue' => (string)$chat->usuario_2],
                        'last_message' => ['stringValue' => 'Chat iniciado...'],
                        'last_message_at' => ['timestampValue' => now()->toRfc3339String()],
                        'otro_nombre' => ['stringValue' => $chat->usuario2->nombre]
                    ]
                ],
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("❌ Error creando chat en Firestore: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene y sanitiza la configuración de Firebase
     */
    private static function getFirebaseConfig()
    {
        $configJson = config('firebase.service_account');
        if (!$configJson) {
            Log::error("❌ FirestoreService: La configuración firebase.service_account está vacía.");
            return null;
        }

        // 1. Decodificar URL si es necesario
        if (str_contains($configJson, '%')) {
            $configJson = urldecode($configJson);
        }

        // 2. Intentar decodificar JSON
        $config = json_decode($configJson, true);

        // 3. Si falla, intentar reparar backslashes que deberían ser saltos de línea (\n)
        if (json_last_error() !== JSON_ERROR_NONE) {
            $repaired = preg_replace('/\\\\([^n])/', "\\n$1", $configJson);
            $config = json_decode($repaired, true);
        }

        if (!$config) {
            Log::error("❌ FirestoreService: El JSON es inválido. Error: " . json_last_error_msg());
            return null;
        }

        // 4. Limpieza "brutal" de la clave privada para OpenSSL
        if (isset($config['private_key'])) {
            $key = $config['private_key'];
            $body = str_replace([
                '-----BEGIN PRIVATE KEY-----', 
                '-----END PRIVATE KEY-----', 
                "\n", "\r", " ", "\\n", "\\"
            ], '', $key);
            
            $config['private_key'] = "-----BEGIN PRIVATE KEY-----\n" . 
                                   chunk_split($body, 64, "\n") . 
                                   "-----END PRIVATE KEY-----";
        }

        return $config;
    }

    /**
     * Genera un Access Token para Google API
     */
    private static function getAccessToken($config)
    {
        $now = time();
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'iss'   => $config['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging https://www.googleapis.com/auth/datastore',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = '';
        if (!openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $config['private_key'], 'SHA256')) {
            Log::error("❌ getAccessToken: Error al firmar el JWT con openssl_sign. Verifique la clave privada.");
            return null;
        }
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

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
            Log::error("❌ getAccessToken: Error en la petición a Google OAuth: " . $e->getMessage());
            return null;
        }
    }
}
