<?php

namespace Database\Factories;

use App\Enums\SeatStatus;
use App\Models\Seat;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    public static ?DateTime $reservedAt = null;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);

        self::$reservedAt = $this->faker->dateTimeBetween('2024-01-01 00:00:00', '-2 minutes');
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seatPositions = [];
        for ($row = 1; $row <= Seat::ROWS; $row++) {
            for ($column = 1; $column <= Seat::COLUMNS; $column++) {
                $seatPositions[] = ['row' => $row, 'column' => $column];
            }
        }
        $seatPosition = $this->faker->unique()->randomElement($seatPositions);

        $seatStatus = $this->faker->randomElement(SeatStatus::cases());
        $reservedAt = $seatStatus !== SeatStatus::FREE
            ? self::$reservedAt
            : null;

        return [
            'row' => $seatPosition['row'],
            'column' => $seatPosition['column'],
            'status' => $seatStatus->value,
            'reserved_at' => $reservedAt,
        ];
    }

    public function free(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => SeatStatus::FREE->value,
                'reserved_at' => null,
            ];
        });
    }

    public function taken(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => SeatStatus::TAKEN->value,
                'reserved_at' => self::$reservedAt,
            ];
        });
    }
}
