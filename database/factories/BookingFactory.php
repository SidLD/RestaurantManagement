<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 20),
            'status' => 'pending',
            'date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'total' => 0,
            'created_at' => Carbon::now()->format('Y-m-d')
        ];
    }
}
