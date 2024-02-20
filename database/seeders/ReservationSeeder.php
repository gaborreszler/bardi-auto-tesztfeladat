<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\ReservationSeat;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $takenSeats = Seat::ALL_SEATS - Seat::$freeSeats;

        Reservation::factory()
            ->count(($takenSeats) / 2)
            ->has(ReservationSeat::factory()->count(2))
            ->create();
    }
}
