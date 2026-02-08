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
            'deposito' => $this->faker->numberBetween(1000, 5000),
            'latitud' => $this->faker->latitude(16.89, 16.92),
            'longitud' => $this->faker->longitude(-92.11, -92.08),
        ];
    }
}
