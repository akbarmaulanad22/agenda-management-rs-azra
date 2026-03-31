<?php

namespace Database\Factories;

use App\Models\Participant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participant>
 */
class ParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'identifier_number' => fake()->numerify('##################'),
            'position' => fake()->randomElement(['Staff', 'Koordinator', 'Analis', 'Kepala Seksi']),
            'department' => fake()->randomElement(['Umum', 'Keuangan', 'SDM', 'IT', 'Perencanaan']),
        ];
    }
}
