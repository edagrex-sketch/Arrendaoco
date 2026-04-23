<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Mensaje;
use App\Models\Inmueble;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $chats = Chat::where(function($query) use ($userId) {
            $query->where('usuario_1', $userId)
                  ->orWhere('usuario_2', $userId);
        })
            ->withCount(['mensajes as unread_count' => function($query) use ($userId) {
                $query->where('sender_id', '!=', $userId)
                      ->where('leido', false);
            }])
            ->with(['usuario1', 'usuario2', 'inmueble'])
            ->orderByDesc('last_message_at')
            ->get();

        return view('chats.index', compact('chats'));
    }

    public function show(Chat $chat)
    {
        // Verificar que el usuario pertenece al chat
        if ($chat->usuario_1 !== Auth::id() && $chat->usuario_2 !== Auth::id()) {
            abort(403);
        }

        $userId = Auth::id();
        $chats = Chat::where(function($query) use ($userId) {
            $query->where('usuario_1', $userId)
                  ->orWhere('usuario_2', $userId);
        })
            ->withCount(['mensajes as unread_count' => function($query) use ($userId) {
                $query->where('sender_id', '!=', $userId)
                      ->where('leido', false);
            }])
            ->with(['usuario1', 'usuario2', 'inmueble'])
            ->orderByDesc('last_message_at')
            ->get();

        $mensajes = $chat->mensajes()->with('sender')->orderBy('created_at', 'asc')->get();
        
        // Marcar mensajes como leídos
        $unredCount = $chat->mensajes()
            ->where('sender_id', '!=', Auth::id())
            ->where('leido', false)
            ->count();

        if ($unredCount > 0) {
            $chat->mensajes()->where('sender_id', '!=', Auth::id())->update(['leido' => true]);
            broadcast(new \App\Events\MessagesRead($chat->id, Auth::id()))->toOthers();
        }

        return view('chats.show', compact('chat', 'mensajes', 'chats'));
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        \Log::info("📨 Intento de envío de mensaje en chat ID: {$chat->id} por usuario ID: " . \Auth::id());

        // Validación de pertenencia (Seguridad adicional)
        if ($chat->usuario_1 !== \Auth::id() && $chat->usuario_2 !== \Auth::id()) {
            \Log::warning("⛔ Intento de envío de mensaje no autorizado en chat ID: {$chat->id}");
            return response()->json(['error' => 'No autorizado'], 403);
        }

        try {
            $request->validate([
                'contenido' => 'required|string',
                'parent_id' => 'nullable|exists:mensajes,id',
                'tipo'      => 'nullable|string'
            ]);
    
            $mensaje = $chat->mensajes()->create([
                'sender_id' => \Auth::id(),
                'contenido' => $request->contenido,
                'parent_id' => $request->parent_id,
                'tipo'      => $request->tipo ?? 'texto',
            ]);
    
            $chat->update([
                'last_message' => $request->contenido,
                'last_message_at' => now()
            ]);

            \Log::info("✅ Mensaje guardado en BD local: ID {$mensaje->id}");
    
            // Disparar evento para tiempo real (Broadcasting)
            try {
                broadcast(new MessageSent($mensaje->load('parent')))->toOthers();
            } catch (\Exception $e) {
                \Log::error("❌ Error en Broadcasting (Reverb): " . $e->getMessage());
            }

            // Notificación persistente
            try {
                $receiverId = ($chat->usuario_1 == \Auth::id()) ? $chat->usuario_2 : $chat->usuario_1;
                \App\Services\NotificationService::send(
                    $receiverId,
                    'Nuevo mensaje de ' . \Auth::user()->nombre,
                    \Illuminate\Support\Str::limit($request->contenido, 50),
                    'mensaje',
                    $chat->id
                );
            } catch (\Exception $e) {
                \Log::error("❌ Error en NotificationService: " . $e->getMessage());
            }

            // Sincronización Real-Time con Firebase Firestore
            try {
                \App\Services\FirestoreService::syncMessage(
                    $chat->getFirebaseId(),
                    \Auth::id(),
                    $request->contenido
                );
            } catch (\Exception $e) {
                \Log::error("❌ Error en FirestoreService: " . $e->getMessage());
            }
     
            return response()->json([
                'success' => true,
                'mensaje' => $mensaje->load(['sender', 'parent'])
            ]);

        } catch (\Exception $e) {
            \Log::error("❌ ERROR FATAL al enviar mensaje: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function startChat($otroUsuarioId, $inmuebleId = null)
    {
        $authId = Auth::id();

        // Ordenar IDs para consistencia
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

        // Sincronizar con Firebase para que aparezca en el móvil
        try {
            \App\Services\FirestoreService::syncChat($chat);
        } catch (\Exception $e) {
            \Log::error("❌ Error sincronizando chat inicial: " . $e->getMessage());
        }

        return redirect()->route('chats.show', $chat);
    }
}
