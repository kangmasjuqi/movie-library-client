<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test Aldmic User',
            'username' => 'aldmic',
            'email' => 'testuser@example.com', // Change this email as needed
            'password' => Hash::make('123abc123'), // Hash the password
        ]);
    }
}
