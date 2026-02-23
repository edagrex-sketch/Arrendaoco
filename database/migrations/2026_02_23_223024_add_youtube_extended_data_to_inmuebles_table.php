<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->string('video_canal')->nullable()->after('video_titulo');
            $table->text('video_descripcion')->nullable()->after('video_canal');
            $table->string('video_duracion')->nullable()->after('video_descripcion'); // ISO 8601: PT3M45S
            $table->unsignedBigInteger('video_vistas')->nullable()->after('video_duracion');
            $table->unsignedBigInteger('video_likes')->nullable()->after('video_vistas');
            $table->timestamp('video_publicado_en')->nullable()->after('video_likes');
            $table->timestamp('video_actualizado_en')->nullable()->after('video_publicado_en');
        });
    }

    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->dropColumn([
                'video_canal',
                'video_descripcion',
                'video_duracion',
                'video_vistas',
                'video_likes',
                'video_publicado_en',
                'video_actualizado_en',
            ]);
        });
    }
};
