<?php

namespace Database\Factories;

use App\Models\Agenda;
use App\Models\InvitationTemplate;
use App\Models\Signer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agenda>
 */
class AgendaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['Rapat Koordinasi', 'Rapat Evaluasi', 'Rapat Perencanaan', 'Sosialisasi Program']),
            'description' => fake()->sentence(),
            'location' => fake()->randomElement(['Ruang Rapat Utama', 'Aula Gedung A', 'Ruang Meeting Lt. 2']),
            'event_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'event_time' => fake()->randomElement(['08:00', '09:00', '10:00', '13:00', '14:00']),
            'status' => 'draft',
            'template_id' => InvitationTemplate::factory(),
            'created_by_signer_id' => Signer::factory(),
            'validated_by_signer_id' => Signer::factory(),
        ];
    }
}
