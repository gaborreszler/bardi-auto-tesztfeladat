<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_cannot_see_reservation(): void
    {
        $reservation = Reservation::factory()->create();

        $response = $this
            ->get(route('reservations.edit', ['reservation' => $reservation]));

        $response
            ->assertRedirectToRoute('login');
    }

    public function test_authenticated_user_can_see_reservation(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('reservations.edit', ['reservation' => $reservation]));

        $response
            ->assertStatus(200);
    }

    public function test_authenticated_user_can_reserve_one_seat(): void
    {
        $user = User::factory()->create();
        $seats = Seat::factory()->free()->count(1)->create();

        $response = $this
            ->actingAs($user)
            ->post(route('reservations.store'), ['seat' => $seats->pluck('id')]);

        $reservation = $user->reservations()->latest()->first();

        $response
            ->assertStatus(302)
            ->assertRedirectToRoute('reservations.edit', compact('reservation'))
            ->assertSessionHas('status', 'Successful reservation. You have now 2 minutes left to make the payment!');
    }

    public function test_authenticated_user_can_reserve_two_seats(): void
    {
        $user = User::factory()->create();
        $seats = Seat::factory()->free()->count(2)->create();

        $response = $this
            ->actingAs($user)
            ->post(route('reservations.store'), ['seat' => $seats->pluck('id')]);

        $reservation = $user->reservations()->latest()->first();

        $response
            ->assertRedirectToRoute('reservations.edit', compact('reservation'))
            ->assertSessionHas('status', 'Successful reservation. You have now 2 minutes left to make the payment!');
    }

    public function test_authenticated_user_cannot_reserve_more_than_two_seats(): void
    {
        $user = User::factory()->create();
        $seats = Seat::factory()->free()->count(3)->create();

        $response = $this
            ->actingAs($user)
            ->post(route('reservations.store'), ['seat' => $seats->pluck('id')]);

        $response
            ->assertInvalid([
                'seat' => 'There must not be more than 2 seat(s) selected.',
            ]);
    }

    public function test_authenticated_user_cannot_complete_reservation_with_invalid_inputs(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('reservations.update', ['reservation' => $reservation]));

        $response
            ->assertInvalid([
                'paid' => 'The paid field must be accepted.',
                'email' => 'The email field is required.',
            ]);
    }

    public function test_authenticated_user_can_complete_reservation(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('reservations.update', ['reservation' => $reservation]), [
                'paid' => 'on',
                'email' => 'example@domain.tld',
            ]);

        $response
            ->assertRedirectToRoute('seats.index')
            ->assertSessionHas('success', 'Seat reservation successfully paid and finalized, confirmation email has been sent.');
    }
}
