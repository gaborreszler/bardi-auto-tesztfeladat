<?php

namespace Database\Seeders;

use App\Enums\SeatStatus;
use App\Models\Seat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seat::factory()
            ->count(Seat::$freeSeats)
            ->free()
            ->create();

        //$this->createSeats(SeatStatus::TAKEN, Seat::ALL_SEATS - Seat::$freeSeats);
    }

    private function createSeats(SeatStatus $type = SeatStatus::TAKEN, int $count = 1): void
    {
        $factory = Seat::factory()
            ->count($count);

        $factory = match ($type->value) {
            SeatStatus::FREE->value => $factory->free(),
            SeatStatus::TAKEN->value => $factory->taken(),
        };

        $factory->create();
    }
}
