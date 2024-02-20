<?php

namespace App\Http\Controllers;

use App\Enums\SeatStatus;
use App\Events\ReservationPaid;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\ReservationSeat;
use App\Models\Seat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $validated = $request->validated();

        $seatIds = $validated['seat'];

        $occupiedSeats = Seat::query()
            ->whereIn('id', $seatIds)
            ->whereNot('status', SeatStatus::FREE->value)
            ->exists();

        if ($occupiedSeats) {
            return response()->json(['error' => 'Some selected seats are already occupied.'], 422);
        }

        Seat::query()
            ->whereIn('id', $seatIds)
            ->update([
                'status' => SeatStatus::RESERVED->value,
                'reserved_at' => Carbon::now(),
            ]);

        $reservation = new Reservation();
        $reservation->user_id = Auth::id();
        $reservation->save();

        foreach ($seatIds as $seatId) {
            $reservationSeat = new ReservationSeat();
            $reservationSeat->reservation_id = $reservation->id;
            $reservationSeat->seat_id = $seatId;
            $reservationSeat->save();
        }

        $request->session()->flash('status', 'Successful reservation. You have now 2 minutes left to make the payment!');

        return redirect()->route('reservations.edit', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        return view('reservation.edit', compact('reservation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();

        $reservation->paid = $validated['paid'] === 'on';
        $reservation->email = $validated['email'];
        $reservation->save();

        foreach ($reservation->reservationSeats as $reservationSeat) {
            $reservationSeat->seat->status = SeatStatus::TAKEN->value;
            $reservationSeat->seat->save();
        }

        ReservationPaid::dispatch($reservation);

        return redirect()->route('seats.index')->with('success', 'Seat reservation successfully paid and finalized, confirmation email has been sent.');
    }
}
