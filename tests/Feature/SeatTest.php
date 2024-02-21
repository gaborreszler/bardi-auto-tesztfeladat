<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeatTest extends TestCase
{
    public function test_guest_user_cannot_see_seats(): void
    {
        $response = $this
            ->get(route('seats.index'));

        $response->assertRedirectToRoute('login');
    }

    public function test_authenticated_user_can_see_seats(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('seats.index'));

        $response->assertStatus(200);
    }
}
