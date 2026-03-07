<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->dropColumn([
                'video_youtube',
                'video_youtube_id',
                'video_thumbnail',
                'video_titulo',
                'video_canal',
                'video_descripcion',
                'video_duracion',
                'video_vistas',
                'video_likes',
                'video_publicado_en',
                'video_actualizado_en'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->string('video_youtube')->nullable()->after('longitud');
            $table->string('video_youtube_id')->nullable()->after('video_youtube');
            $table->string('video_thumbnail')->nullable()->after('video_youtube_id');
            $table->string('video_titulo')->nullable()->after('video_thumbnail');
            $table->string('video_canal')->nullable()->after('video_titulo');
            $table->text('video_descripcion')->nullable()->after('video_canal');
            $table->string('video_duracion')->nullable()->after('video_descripcion');
            $table->unsignedBigInteger('video_vistas')->nullable()->after('video_duracion');
            $table->unsignedBigInteger('video_likes')->nullable()->after('video_vistas');
            $table->timestamp('video_publicado_en')->nullable()->after('video_likes');
            $table->timestamp('video_actualizado_en')->nullable()->after('video_publicado_en');
        });
    }
};
