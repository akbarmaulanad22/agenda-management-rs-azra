<?php

namespace Database\Factories;

use App\Models\Signer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Signer>
 */
class SignerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement(['Kepala Bagian', 'Sekretaris', 'Direktur', 'Wakil Direktur']),
            'signature_path' => null,
        ];
    }
}
