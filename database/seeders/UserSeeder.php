<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $creatorRole = Role::where('name', 'creator')->first();
        $customerRole = Role::where('name', 'customer')->first();

        User::firstOrCreate(
            ['email' => 'creator@example.com'],
            [
                'name' => 'Creator User',
                'email' => 'creator@example.com',
                'password' => Hash::make('password'),
                'role_id' => $creatorRole->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'role_id' => $customerRole->id,
            ]
        );
    }
}