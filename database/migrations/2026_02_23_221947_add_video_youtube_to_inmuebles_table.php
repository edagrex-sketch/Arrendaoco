<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->string('video_youtube')->nullable()->after('longitud');
            $table->string('video_youtube_id')->nullable()->after('video_youtube');
            $table->string('video_thumbnail')->nullable()->after('video_youtube_id');
            $table->string('video_titulo')->nullable()->after('video_thumbnail');
        });
    }

    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->dropColumn(['video_youtube', 'video_youtube_id', 'video_thumbnail', 'video_titulo']);
        });
    }
};
