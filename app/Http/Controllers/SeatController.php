<?php

namespace App\Http\Controllers;

use App\Models\Seat;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seats = Seat::all();

        return view('seat.index', compact('seats'));
    }
}
