<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Seat extends Model
{
    use HasFactory;

    public const int ROWS = 4;
    public const int COLUMNS = 12;
    public const int ALL_SEATS = self::ROWS * self::COLUMNS;
    public static int $freeSeats = 2;

    public function reservationSeat(): HasOne
    {
        return $this->hasOne(ReservationSeat::class);
    }

    public function reservation(): HasOneThrough
    {
        return $this->hasOneThrough(Reservation::class, ReservationSeat::class);
    }
}
