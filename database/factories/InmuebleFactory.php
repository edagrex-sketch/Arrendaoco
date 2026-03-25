<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inmueble>
 */
class InmuebleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(4), // Oración corta atractiva
            'direccion' => $this->faker->address(),
            'renta_mensual' => $this->faker->numberBetween(1500, 9000),
            'tipo' => $this->faker->randomElement(['Casa', 'Departamento', 'Cuarto']),
            'habitaciones' => $this->faker->numberBetween(1, 5),
            'banos' => $this->faker->numberBetween(1, 3),
            'metros' => $this->faker->numberBetween(40, 250),
            'imagen' => 'https://picsum.photos/800/600',
            'descripcion' => $this->faker->paragraph(),
            'estatus' => 'disponible', // Equivalente a disponible: true
            'propietario_id' => 1, // User ID por defecto 1
            'ciudad' => 'Ocosingo',
            'estado' => 'Chiapas',
            'codigo_postal' => '29950',
            'deposito' => function (array $attributes) {
                return $attributes['renta_mensual'];
            },
            'latitud' => $this->faker->latitude(16.89, 16.92),
            'longitud' => $this->faker->longitude(-92.11, -92.08),

            // Nuevos campos extendidos
            'estado_mobiliario' => $this->faker->randomElement(['amueblada', 'semiamueblada', 'no amueblada']),
            'tiene_cerradura_propia' => $this->faker->boolean(),
            'cantidad_llaves' => $this->faker->numberBetween(1, 4),
            'tiene_estacionamiento' => $this->faker->boolean(),
            'momento_pago' => $this->faker->randomElement(['adelantado', 'vencido']),
            'dias_tolerancia' => $this->faker->numberBetween(0, 5),
            'dias_preaviso' => $this->faker->numberBetween(15, 60),
            'permite_mascotas' => $permite = $this->faker->boolean(),
            'incluir_clausulas' => $incluye = $this->faker->boolean(),
            'clausulas_extra' => $incluye ? $this->faker->sentence(10) : null,
            'requiere_deposito' => $this->faker->boolean(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (\App\Models\Inmueble $inmueble) {
            // Mascotas
            if ($inmueble->permite_mascotas) {
                $inmueble->mascotas()->attach(\App\Models\Mascota::inRandomOrder()->take(rand(1, 3))->pluck('id'));
            }
            
            // Zonas Comunes
            if ($inmueble->tipo === 'Cuarto') {
                $inmueble->zonasComunes()->attach(\App\Models\ZonaComun::inRandomOrder()->take(rand(1, 4))->pluck('id'));
            }

            // Servicios Relacionales
            $serviciosNombres = ['Agua', 'Electricidad', 'Gas', 'Internet', 'TV por Cable'];
            $seleccionados = (array) array_rand(array_flip($serviciosNombres), rand(2, 4));
            foreach($seleccionados as $srv) {
                $inmueble->servicios()->create([
                    'servicio' => $srv,
                    'paga' => rand(0, 1) ? 'arrendador' : 'inquilino'
                ]);
            }
        });
    }
}
