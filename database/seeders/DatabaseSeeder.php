<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'মোঃ রুহুল আমিন',
            'email' => 'ruhul11bd@gmail.com',
            'password' => Hash::make('01751raj'),
            'status' => 'active',
        ]);
    }
}
