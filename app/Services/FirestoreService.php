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
            $accessToken = self::getAccessToken();
            if (!$accessToken) return;

            $configJson = env('FCM_SERVICE_ACCOUNT_JSON');
            $config = json_decode($configJson, true);
            $projectId = $config['project_id'] ?? 'arrendaoco-fad79';

            // Estructura para Firestore (Rest API)
            // chats/{chatId}/mensajes/{random_id}
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/chats/{$chatId}/mensajes";

            $client = new Client();
            $client->post($url, [
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

            // Actualizar el puntero del último mensaje en el documento padre del chat
            $parentUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/chats/{$chatId}?updateMask.fieldPaths=last_message&updateMask.fieldPaths=updated_at";
            $client->patch($parentUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'fields' => [
                        'last_message' => ['stringValue' => $texto],
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
     * Genera un Access Token para Google API (mismo método que NotificationService)
     */
    private static function getAccessToken()
    {
        $configJson = env('FCM_SERVICE_ACCOUNT_JSON');
        if (!$configJson) return null;

        $config = json_decode($configJson, true);
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
        openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $config['private_key'], 'SHA256');
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
            return null;
        }
    }
}
