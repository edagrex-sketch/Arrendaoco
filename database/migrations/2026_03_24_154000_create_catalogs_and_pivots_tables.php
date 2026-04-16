<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('inmueble_servicio');
        Schema::dropIfExists('inmueble_mobiliario');
        Schema::dropIfExists('inmueble_zona_comun');
        Schema::dropIfExists('inmueble_mascota');
        Schema::dropIfExists('zonas_comunes');
        Schema::dropIfExists('mobiliarios');
        Schema::dropIfExists('mascotas');

        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('mobiliarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('zonas_comunes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('inmueble_mascota', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('inmueble_id');
            $table->foreign('inmueble_id')->references('id')->on('inmuebles')->onDelete('cascade');
            $table->foreignId('mascota_id')->constrained('mascotas')->onDelete('cascade');
        });

        Schema::create('inmueble_zona_comun', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('inmueble_id');
            $table->foreign('inmueble_id')->references('id')->on('inmuebles')->onDelete('cascade');
            $table->foreignId('zona_comun_id')->constrained('zonas_comunes')->onDelete('cascade');
        });

        Schema::create('inmueble_mobiliario', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('inmueble_id');
            $table->foreign('inmueble_id')->references('id')->on('inmuebles')->onDelete('cascade');
            $table->foreignId('mobiliario_id')->constrained('mobiliarios')->onDelete('cascade');
        });

        Schema::create('inmueble_servicio', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('inmueble_id');
            $table->foreign('inmueble_id')->references('id')->on('inmuebles')->onDelete('cascade');
            $table->string('servicio');
            $table->enum('paga', ['arrendador', 'inquilino']);
        });

        // Seed default catalog data
        $mascotas = [
            'Perros', 'Gatos', 'Pericos y loros', 'Pájaros de canto', 'Peces',
            'Hamsters y ratones', 'Conejos', 'Tortugas', 'Iguanas y lagartijas',
            'Serpientes', 'Ranas y ajolotes', 'Hurones', 'Arañas y tarántulas',
            'Cuyos', 'Pollos y gallinas', 'Otros'
        ];
        foreach($mascotas as $m) {
            DB::table('mascotas')->insert(['nombre' => $m, 'slug' => Str::slug($m, '_'), 'created_at' => now(), 'updated_at' => now()]);
        }

        $mobiliarios = ['Cama', 'Escritorio', 'Silla', 'Armario', 'Sofá', 'Mesa de centro', 'Comedor', 'Librero', 'Buros'];
        foreach($mobiliarios as $m) {
            DB::table('mobiliarios')->insert(['nombre' => $m, 'slug' => Str::slug($m, '_'), 'created_at' => now(), 'updated_at' => now()]);
        }

        $zonas = ['Sala', 'Cocina', 'Jardín', 'Patio', 'Balcón', 'Comedor', 'Estudio'];
        foreach($zonas as $z) {
            DB::table('zonas_comunes')->insert(['nombre' => $z, 'slug' => Str::slug($z, '_'), 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inmueble_servicio');
        Schema::dropIfExists('inmueble_mobiliario');
        Schema::dropIfExists('inmueble_zona_comun');
        Schema::dropIfExists('inmueble_mascota');
        Schema::dropIfExists('zonas_comunes');
        Schema::dropIfExists('mobiliarios');
        Schema::dropIfExists('mascotas');
    }
};
