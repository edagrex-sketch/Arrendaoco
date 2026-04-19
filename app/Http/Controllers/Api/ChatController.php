<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Mensaje;
use App\Events\MessageSent;
use App\Events\MessagesRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $chats = Chat::where('usuario_1', '=', $userId)
                  ->orWhere('usuario_2', '=', $userId)
            ->withCount(['mensajes as unread_count' => function($query) use ($userId) {
                $query->where('sender_id', '!=', $userId)
                      ->where('leido', false);
            }])
            ->with(['usuario1', 'usuario2', 'inmueble'])
            ->orderByDesc('last_message_at')
            ->get();

        return response()->json([
            'data' => $chats->map(function($chat) use ($userId) {
                $otro = $chat->getOtroUsuario($userId);
                return [
                    'id' => $chat->id,
                    'otro_usuario' => [
                        'id' => $otro->id,
                        'nombre' => $otro->nombre,
                        'foto_perfil' => $otro->foto_perfil,
                    ],
                    'inmueble' => $chat->inmueble ? [
                        'id' => $chat->inmueble->id,
                        'titulo' => $chat->inmueble->titulo,
                        'imagen' => $chat->inmueble->imagen,
                    ] : null,
                    'last_message' => $chat->last_message,
                    'last_message_at' => $chat->last_message_at,
                    'unread_count' => $chat->unread_count,
                    'activo' => $chat->activo
                ];
            })
        ]);
    }

    public function messages(Chat $chat)
    {
        if ($chat->usuario_1 !== Auth::id() && $chat->usuario_2 !== Auth::id()) {
            abort(403);
        }

        $mensajes = $chat->mensajes()
            ->with(['sender', 'parent'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Marcar commo leídos
        $chat->mensajes()
            ->where('sender_id', '!=', Auth::id())
            ->where('leido', false)
            ->update(['leido' => true]);
            
        broadcast(new MessagesRead($chat->id, Auth::id()))->toOthers();

        return response()->json([
            'data' => $mensajes
        ]);
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        if ($chat->usuario_1 !== Auth::id() && $chat->usuario_2 !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'contenido' => 'required|string',
            'parent_id' => 'nullable|exists:mensajes,id',
            'tipo'      => 'nullable|string',
            'metadata'  => 'nullable|array'
        ]);
 
        $mensaje = $chat->mensajes()->create([
            'sender_id' => Auth::id(),
            'contenido' => $request->contenido,
            'parent_id' => $request->parent_id,
            'tipo'      => $request->tipo ?? 'texto',
            'metadata'  => $request->metadata,
        ]);
 
        $chat->update([
            'last_message' => $request->contenido,
            'last_message_at' => now()
        ]);
 
        $mensajeCompleto = $mensaje->load(['sender', 'parent']);
        broadcast(new MessageSent($mensajeCompleto))->toOthers();

        // Notificación Push y Persistente
        $otro = $chat->getOtroUsuario(Auth::id());
        if ($otro) {
            \App\Services\NotificationService::send(
                $otro->id,
                'Nuevo mensaje de ' . Auth::user()->nombre,
                \Illuminate\Support\Str::limit($request->contenido, 50),
                'mensaje',
                $chat->id
            );

            // Sincronización Real-Time con Firebase Firestore (Móvil)
            \App\Services\FirestoreService::syncMessage(
                $chat->getFirebaseId(),
                Auth::id(),
                $request->contenido
            );
        }
 
        return response()->json([
            'success' => true,
            'data' => $mensajeCompleto
        ]);
    }

    public function sendToUser(Request $request, $otroUsuarioId)
    {
        $authId = Auth::id();
        $id1 = min($authId, $otroUsuarioId);
        $id2 = max($authId, $otroUsuarioId);

        $chat = Chat::firstOrCreate([
            'usuario_1' => $id1,
            'usuario_2' => $id2,
        ], [
            'activo' => true,
            'last_message_at' => now()
        ]);

        return $this->sendMessage($request, $chat);
    }

    public function startChat($otroUsuarioId, $inmuebleId = null)
    {
        $authId = Auth::id();
        if ($authId == $otroUsuarioId) {
            return response()->json(['message' => 'No puedes chatear contigo mismo'], 422);
        }

        $id1 = min($authId, $otroUsuarioId);
        $id2 = max($authId, $otroUsuarioId);

        $chat = Chat::firstOrCreate(
            [
                'usuario_1' => $id1,
                'usuario_2' => $id2,
                'inmueble_id' => $inmuebleId
            ],
            [
                'activo' => true,
                'last_message_at' => now()
            ]
        );

        return response()->json([
            'data' => $chat->load(['usuario1', 'usuario2', 'inmueble'])
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = Auth::user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'message' => 'Token actualizado exitosamente',
        ]);
    }
}
