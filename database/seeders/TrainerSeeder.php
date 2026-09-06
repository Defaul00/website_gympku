<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $trainerRole = Role::where('name', 'trainer')->firstOrFail();

        $data = [
            ['Andi Pratama', 'Bodybuilding', 8, 150000],
            ['Budi Santoso', 'Strength Training', 10, 125000],
            ['Citra Dewi', 'Cardio', 6, 100000],
            ['Dewi Lestari', 'Yoga', 5, 100000],
            ['Eko Wijaya', 'HIIT', 7, 120000],
            ['Fajar Nugroho', 'Physiotherapy', 12, 175000],
            ['Gita Maharani', 'Nutrition', 4, 90000],
            ['Hendra Gunawan', 'Strength Training', 9, 135000],
            ['Intan Permata', 'Cardio', 3, 80000],
            ['Joko Susilo', 'Bodybuilding', 11, 140000],
            ['Kartika Sari', 'Yoga', 6, 105000],
            ['Lukman Hakim', 'Functional', 5, 95000],
        ];

        foreach ($data as [$name, $specialization, $experienceYears, $hourlyRate]) {
            $user = User::query()->updateOrCreate(
                ['email' => str($name)->slug('.') . '@physiogym.com'],
                [
                    'role_id' => $trainerRole->id,
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'phone' => '08' . fake()->numerify('##########'),
                    'gender' => fake()->randomElement(['male', 'female']),
                ],
            );

            Trainer::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $specialization,
                    'bio' => "Pelatih {$specialization} bersertifikat dengan {$experienceYears} tahun pengalaman.",
                    'experience_years' => $experienceYears,
                    'hourly_rate' => $hourlyRate,
                    'is_available' => true,
                ],
            );
        }
    }
}
