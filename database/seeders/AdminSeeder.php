<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();

        User::query()->updateOrCreate(['email' => 'admin@physiogym.com'], [
            'role_id' => $adminRole->id,
            'name' => 'Admin Physio Gym',
            'email' => 'admin@physiogym.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '085311716767',
            'gender' => 'male',
            'address' => 'Jl. Mangga No.10a, Pekanbaru',
        ]);
    }
}
