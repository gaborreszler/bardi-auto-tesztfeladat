<?php

namespace App\Listeners;

use App\Events\ReservationPaid;
use App\Mail\ReservationCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailReservationCompleted implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReservationPaid $event): void
    {
        $reservation = $event->reservation;

        Mail::to($reservation->email)->queue(new ReservationCompleted($reservation));
    }
}
