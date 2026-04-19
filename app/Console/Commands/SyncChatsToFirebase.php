<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mensaje;
use App\Models\Chat;
use App\Services\FirestoreService;

class SyncChatsToFirebase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'chat:sync-to-firebase';

    /**
     * The console command description.
     */
    protected $description = 'Migra todos los mensajes de MySQL a Firebase Firestore para sincronización móvil';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando migración de chats a Firebase...');

        $mensajes = Mensaje::with('chat')->orderBy('created_at', 'asc')->get();
        $total = $mensajes->count();

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($mensajes as $mensaje) {
            if ($mensaje->chat) {
                FirestoreService::syncMessage(
                    $mensaje->chat->getFirebaseId(),
                    $mensaje->sender_id,
                    $mensaje->contenido
                );
            }
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✅ Migración completada. {$total} mensajes ya viven en Firebase.");
    }
}
