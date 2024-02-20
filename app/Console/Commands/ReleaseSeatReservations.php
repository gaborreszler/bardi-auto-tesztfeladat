<?php

namespace App\Console\Commands;

use App\Enums\SeatStatus;
use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ReleaseSeatReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:release-seat-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Releases expired and unpaid reservations after 2 minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reservations = Reservation::query()
            ->where('paid', false)
            ->whereHas('reservationSeats.seat', function (Builder $query) {
                $query
                    ->where('status', SeatStatus::RESERVED->value)
                    ->where('reserved_at', '<', Carbon::now()->subMinutes(2));
            })
            ->with([
                'reservationSeats',
                'reservationSeats.seat',
            ])
            ->get();

        foreach ($reservations as $reservation) {
            foreach ($reservation->reservationSeats as $reservationSeat) {
                $seat = $reservationSeat->seat;
                $seat->status = SeatStatus::FREE->value;
                $seat->reserved_at = null;
                $seat->save();

                $reservationSeat->delete();
            }

            $reservation->delete();
        }

        $this->info('Expired and unpaid reservations of seats have been released.');
    }
}
