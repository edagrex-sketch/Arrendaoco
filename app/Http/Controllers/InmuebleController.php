<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inmueble;
use Illuminate\Support\Facades\DB; // Necesario para transacciones
use Illuminate\Support\Facades\Storage;

class InmuebleController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validación en Español
        $rules = [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'precio' => 'required|numeric',
            'habitaciones' => 'required|integer',
            'banos' => 'required|integer',
            'metros' => 'required|numeric',
            'descripcion' => 'required|string',
            'direccion' => 'required|string',
            'imagenes' => 'required|array|min:1|max:10',
            'imagenes.*' => 'image|max:10240', // Aumentamos a 10MB por si acaso
        ];

        $messages = [
            'nombre.required' => 'El título del anuncio es obligatorio.',
            'tipo.required' => 'Debes seleccionar un tipo de inmueble.',
            'precio.required' => 'El precio es obligatorio.',
            'habitaciones.required' => 'Indica el número de habitaciones.',
            'banos.required' => 'Indica el número de baños.',
            'metros.required' => 'Indica el tamaño en m².',
            'descripcion.required' => 'La descripción no puede estar vacía.',
            'direccion.required' => 'La dirección es obligatoria.',
            'imagenes.required' => 'Debes subir al menos una foto.',
            'imagenes.min' => 'Sube al menos una foto.',
            'imagenes.max' => 'Máximo 10 fotos permitidas.',
            'imagenes.*.image' => 'El archivo debe ser una imagen (JPG, PNG).',
            'imagenes.*.max' => 'Una de las imágenes es muy pesada (Máx 10MB).',
        ];

        $validated = $request->validate($rules, $messages);

        try {
            DB::beginTransaction(); // Todo o nada

            // 2. Crear el Inmueble Base
            $inmueble = new Inmueble();
            $inmueble->titulo = $request->nombre;
            $inmueble->descripcion = $request->descripcion;
            $inmueble->direccion = $request->direccion;
            $inmueble->tipo = $request->tipo;
            $inmueble->renta_mensual = $request->precio;
            $inmueble->habitaciones = $request->habitaciones;
            $inmueble->banos = $request->banos;
            $inmueble->metros = $request->metros;
            $inmueble->propietario_id = auth()->id();
            
            // Datos default obligatorios
            $inmueble->ciudad = 'Ocosingo';
            $inmueble->estado = 'Chiapas';
            $inmueble->codigo_postal = '29950';

            // Guardamos la RUTA de la primera imagen como "portada" en la tabla principal para facilitar consultas
            // (Esto es opcional pero muy útil para el listado del Home)
            $primeraImagen = $request->file('imagenes')[0];
            $pathPortada = $primeraImagen->store('inmuebles', 'public');
            $inmueble->imagen = '/storage/' . $pathPortada;

            $inmueble->save();

            // 3. Guardar TODAS las imágenes en la tabla relacionada
            foreach ($request->file('imagenes') as $foto) {
                $path = $foto->store('inmuebles', 'public');
                
                // Insertar en tabla secundaria (usamos DB directo para rápido, o crea un Modelo si prefieres)
                DB::table('imagenes_inmuebles')->insert([
                    'inmueble_id' => $inmueble->id,
                    'ruta_imagen' => '/storage/' . $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit(); // Confirmar cambios

            return redirect()->route('inicio')->with('success', '¡Propiedad publicada correctamente!');

        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer si algo falla
            return back()->with('error', 'Ocurrió un error al guardar: ' . $e->getMessage());
        }
    }
}