<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $user = User::firstOrCreate(
            ['email' => 'majdadmin@gmail.com'],
            [
                'name' => 'Majd Admin',
                'password' => Hash::make('12345'),
            ]
        );
        $user->assignRole('admin');
    }
}
