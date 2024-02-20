<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Bárdi Autó',
            'email' => 'no-reply@bardiauto.hu',
            'password' => Hash::make('BardiAuto-2024'),
        ]);
    }
}
