<?php

namespace Database\Factories;

use App\Models\Agenda;
use App\Models\Employee;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendaFactory extends Factory
{
    protected $model = Agenda::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'event_date' => fake()->dateTimeBetween('now', '+1 month'),
            'event_time' => fake()->time('H:i'),
            'status' => 'draft',
            'organizer_id' => Employee::factory(),
            'meeting_chair_id' => Employee::factory(),
            'room_id' => Room::factory(),
            'type' => 'rapat',
        ];
    }
}
