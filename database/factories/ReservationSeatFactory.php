<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReservationSeat>
 */
class ReservationSeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seat_id' => Seat::factory()->taken(),
            'reservation_id' => Reservation::factory(),
        ];
    }
}
