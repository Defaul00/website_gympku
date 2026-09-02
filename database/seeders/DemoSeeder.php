<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\GymEquipment;
use App\Models\MemberCard;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Trainer;
use App\Models\TrainerBooking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    private array $hourWeights = [];

    public function run(): void
    {
        $this->hourWeights = $this->buildHourWeights();

        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $trainerRole = Role::where('name', 'trainer')->firstOrFail();
        $memberRole = Role::where('name', 'member')->firstOrFail();

        $this->seedAdmin($adminRole);
        $trainers = $this->seedTrainers($trainerRole);
        $memberships = Membership::all();
        $members = $this->seedMembers($memberRole);
        $cards = $this->seedCards($members, $memberships);
        $this->seedPayments($cards);
        $this->seedAttendances($cards);
        $this->seedBookings($members, $trainers);
        $this->seedAchievements($members);
        $this->seedNotifications($members);
        $this->seedAnnouncements();
        $this->seedEquipments();
    }

    private function seedAdmin(Role $role): void
    {
        User::query()->updateOrCreate(['email' => 'admin@physiogym.com'], [
            'role_id' => $role->id,
            'name' => 'Admin Physio Gym',
            'email' => 'admin@physiogym.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '085311716767',
            'gender' => 'male',
            'address' => 'Jl. Mangga No.10a, Pekanbaru',
        ]);
    }

    private function seedTrainers(Role $role): array
    {
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

        $trainers = [];
        foreach ($data as [$name, $spec, $exp, $rate]) {
            $user = User::create([
                'role_id' => $role->id,
                'name' => $name,
                'email' => str($name)->slug('.') . '@physiogym.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone' => '08' . fake()->numerify('##########'),
                'gender' => fake()->randomElement(['male', 'female']),
            ]);

            $trainers[] = Trainer::create([
                'user_id' => $user->id,
                'specialization' => $spec,
                'bio' => "Pelatih {$spec} bersertifikat dengan {$exp} tahun pengalaman.",
                'experience_years' => $exp,
                'hourly_rate' => $rate,
                'is_available' => true,
            ]);
        }

        return $trainers;
    }

    private function seedMembers(Role $role): array
    {
        $members = User::factory()->count(220)->withRole($role->id)->create();

        return $members->all();
    }

    private function seedCards(array $members, $memberships): array
    {
        $cards = [];

        foreach ($members as $member) {
            $hasActive = fake()->boolean(85);
            $cardCount = $hasActive ? fake()->numberBetween(1, 2) : 1;

            for ($i = 0; $i < $cardCount; $i++) {
                $membership = $memberships->random();
                $monthsAgo = $hasActive ? fake()->numberBetween(1, 10) : fake()->numberBetween(3, 12);
                $start = now()->subMonths($monthsAgo)->subDays(fake()->numberBetween(0, 27));

                if ($hasActive && $i === 0) {
                    $start = now()->subDays(fake()->numberBetween(1, 90));
                }

                $end = $start->copy()->addMonths($membership->duration_months);
                $status = $end->isPast() ? 'expired' : 'active';

                $cards[] = MemberCard::create([
                    'user_id' => $member->id,
                    'membership_id' => $membership->id,
                    'card_number' => 'PG-' . strtoupper(fake()->unique()->bothify('####??##??')) . '-' . $member->id,
                    'start_date' => $start,
                    'end_date' => $end,
                    'status' => $status,
                ]);
            }
        }

        return $cards;
    }

    private function seedPayments(array $cards): void
    {
        $methods = ['transfer', 'qris', 'cash', 'card'];

        foreach ($cards as $card) {
            Payment::create([
                'user_id' => $card->user_id,
                'member_card_id' => $card->id,
                'amount' => $card->membership->price,
                'method' => fake()->randomElement($methods),
                'status' => fake()->randomElement(['paid', 'paid', 'paid', 'pending']),
                'reference' => 'PAY-' . strtoupper(fake()->unique()->bothify('###??###')) . '-' . $card->id,
                'paid_at' => $card->start_date->copy()->addHours(fake()->numberBetween(1, 24)),
            ]);
        }
    }

    private function seedAttendances(array $cards): void
    {
        $rows = [];

        foreach ($cards as $card) {
            $member = $card->user;
            $rangeStart = $card->start_date;
            $rangeEnd = min($card->end_date, now());
            $spanDays = $rangeStart->diffInDays($rangeEnd);

            if ($spanDays < 1) {
                continue;
            }

            $monthSpan = max(1, (int) ceil($spanDays / 30));
            $perMonth = fake()->numberBetween(4, 18);
            $total = min($perMonth * $monthSpan, 60);

            for ($i = 0; $i < $total; $i++) {
                $date = \Illuminate\Support\Carbon::parse(fake()->dateTimeBetween($rangeStart, $rangeEnd));
                $hour = $this->weightedHour();
                $checkIn = $date->setTime($hour, fake()->numberBetween(0, 59));
                $duration = fake()->numberBetween(40, 140);

                $rows[] = [
                    'user_id' => $member->id,
                    'member_card_id' => $card->id,
                    'check_in' => $checkIn->format('Y-m-d H:i:s'),
                    'check_out' => $checkIn->copy()->addMinutes($duration)->format('Y-m-d H:i:s'),
                    'duration_minutes' => $duration,
                    'created_at' => $checkIn->format('Y-m-d H:i:s'),
                    'updated_at' => $checkIn->format('Y-m-d H:i:s'),
                ];
            }

            if (count($rows) >= 500) {
                Attendance::insert($rows);
                $rows = [];
            }
        }

        if (! empty($rows)) {
            Attendance::insert($rows);
        }
    }

    private function seedBookings(array $members, array $trainers): void
    {
        $rows = [];
        $statuses = ['completed', 'completed', 'confirmed', 'pending', 'cancelled'];

        for ($i = 0; $i < 1100; $i++) {
            $date = fake()->dateTimeBetween('-12 months', '+1 month');
            $hour = fake()->numberBetween(7, 19);
            $status = $statuses[array_rand($statuses)];

            $rows[] = [
                'user_id' => $members[array_rand($members)]->id,
                'trainer_id' => $trainers[array_rand($trainers)]->id,
                'booking_date' => $date->format('Y-m-d'),
                'start_time' => sprintf('%02d:00:00', $hour),
                'end_time' => sprintf('%02d:00:00', $hour + 1),
                'status' => $status,
                'notes' => fake()->optional(0.3)->sentence(),
                'created_at' => now()->subDays(fake()->numberBetween(1, 370)),
                'updated_at' => now()->subDays(fake()->numberBetween(1, 370)),
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            TrainerBooking::insert($chunk);
        }
    }

    private function seedAchievements(array $members): void
    {
        $achievements = [
            ['name' => 'First Workout', 'description' => 'Menyelesaikan sesi latihan pertama.', 'icon' => 'star', 'points' => 50, 'badge_color' => '#0ea5e9'],
            ['name' => 'Early Bird', 'description' => 'Check-in sebelum jam 8 pagi sebanyak 10 kali.', 'icon' => 'sun', 'points' => 100, 'badge_color' => '#f59e0b'],
            ['name' => 'Night Owl', 'description' => 'Check-in setelah jam 8 malam sebanyak 10 kali.', 'icon' => 'moon', 'points' => 100, 'badge_color' => '#8b5cf6'],
            ['name' => '10 Hari Beruntun', 'description' => 'Latihan 10 hari berturut-turut.', 'icon' => 'fire', 'points' => 250, 'badge_color' => '#f43f5e'],
            ['name' => '100 Jam Latihan', 'description' => 'Akumulasi 100 jam latihan.', 'icon' => 'clock', 'points' => 300, 'badge_color' => '#10b981'],
            ['name' => 'Streak Champion', 'description' => 'Latihan 30 hari berturut-turut.', 'icon' => 'trophy', 'points' => 500, 'badge_color' => '#6366f1'],
            ['name' => 'Marathon Runner', 'description' => 'Mencapai 50 sesi dalam sebulan.', 'icon' => 'bolt', 'points' => 400, 'badge_color' => '#ec4899'],
            ['name' => 'Fitness Milestone', 'description' => 'Bergabung lebih dari 6 bulan.', 'icon' => 'medal', 'points' => 150, 'badge_color' => '#14b8a6'],
        ];

        foreach ($achievements as $data) {
            Achievement::create($data);
        }

        $achievementIds = Achievement::pluck('id')->all();

        foreach ($members as $member) {
            if (! fake()->boolean(60)) {
                continue;
            }

            $count = fake()->numberBetween(1, 4);
            $chosen = collect($achievementIds)->random(min($count, count($achievementIds)));

            foreach ($chosen as $achievementId) {
                \App\Models\UserAchievement::create([
                    'user_id' => $member->id,
                    'achievement_id' => $achievementId,
                    'unlocked_at' => fake()->dateTimeBetween('-11 months', 'now'),
                ]);
            }
        }
    }

    private function seedNotifications(array $members): void
    {
        $admin = User::where('email', 'admin@physiogym.com')->first();

        $adminNotes = [
            ['system', 'Selamat Datang', 'Selamat datang di dashboard Physio Gym Management System.'],
            ['system', 'Sinkronisasi Selesai', 'Semua data membership telah disinkronkan.'],
            ['system', 'Laporan Siap', 'Laporan bulanan telah tersedia di menu Laporan.'],
        ];

        foreach ($adminNotes as [$type, $title, $body]) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'read_at' => fake()->optional(0.5)->dateTimeBetween('-1 month', 'now'),
            ]);
        }

        $samples = collect($members)->random(min(40, count($members)));

        foreach ($samples as $member) {
            Notification::create([
                'user_id' => $member->id,
                'type' => 'membership',
                'title' => 'Membership Hampir Berakhir',
                'body' => 'Membership Anda akan segera berakhir. Segera perpanjang untuk terus menikmati fasilitas.',
                'read_at' => fake()->optional(0.5)->dateTimeBetween('-2 months', 'now'),
            ]);
        }
    }

    private function seedAnnouncements(): void
    {
        $items = [
            ['info', 'Jam Operasional', 'Gym buka setiap hari mulai pukul 06.00 sampai 22.00 WIB.'],
            ['promo', 'Promo Member Baru', 'Dapatkan diskon 10% untuk pendaftaran membership 6 bulan & 12 bulan selama bulan ini.'],
            ['event', 'Fitness Challenge', 'Ikuti Fitness Challenge bulanan dan menangkan merchandise eksklusif Physio Gym.'],
            ['maintenance', 'Pemeliharaan Alat', 'Terdapat pemeliharaan rutin treadmill area cardio pada hari Minggu pagi.'],
            ['info', 'Kelas Baru', 'Kelas Yoga kini tersedia 3x seminggu dengan instruktur tersertifikasi.'],
            ['promo', 'Paket Personal Training', 'Beli 5 sesi personal training, gratis 1 sesi tambahan.'],
            ['event', 'Body Transformation Contest', 'Daftarkan diri Anda untuk kontes transformasi tubuh terbesar tahun ini.'],
            ['maintenance', 'Area Locker', 'Area locker akan dicat ulang, mohon pergunakan locker cadangan.'],
            ['info', 'Kebijakan Baru', 'Pembatalan booking trainer lebih dari 12 jam sebelum jadwal tidak dikenakan biaya.'],
            ['promo', 'Referral Program', 'Ajak teman bergabung dan dapatkan potongan 50rb di perpanjangan membership Anda.'],
        ];

        foreach ($items as [$type, $title, $body]) {
            Announcement::create([
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
            ]);
        }
    }

    private function seedEquipments(): void
    {
        $items = [
            ['Treadmill Pro-1', 'Cardio', 'good'], ['Treadmill Pro-2', 'Cardio', 'good'],
            ['Treadmill Pro-3', 'Cardio', 'needs_maintenance'], ['Treadmill Pro-4', 'Cardio', 'good'],
            ['Spin Bike A1', 'Cardio', 'good'], ['Elliptical E1', 'Cardio', 'poor'],
            ['Bench Press Flat', 'Strength', 'good'], ['Bench Press Incline', 'Strength', 'good'],
            ['Squat Rack R1', 'Strength', 'good'], ['Squat Rack R2', 'Strength', 'needs_maintenance'],
            ['Leg Press LP1', 'Strength', 'good'], ['Lat Pulldown LD1', 'Strength', 'good'],
            ['Barbell 20kg', 'Free Weights', 'good'], ['Barbell 15kg', 'Free Weights', 'good'],
            ['Dumbbell Set 2-40kg', 'Free Weights', 'good'], ['Kettlebell Set', 'Free Weights', 'good'],
            ['Power Rack PR1', 'Strength', 'good'], ['Cable Crossover', 'Strength', 'good'],
            ['Rowing Machine', 'Cardio', 'good'], ['Stair Climber', 'Cardio', 'needs_maintenance'],
            ['TRX System', 'Functional', 'good'], ['Battle Rope', 'Functional', 'good'],
            ['Medicine Ball Set', 'Functional', 'good'], ['Plyo Box Set', 'Functional', 'good'],
        ];

        foreach ($items as [$name, $category, $condition]) {
            GymEquipment::create([
                'name' => $name,
                'category' => $category,
                'condition' => $condition,
                'last_maintenance' => fake()->dateTimeBetween('-4 months', 'today')->format('Y-m-d'),
                'next_maintenance' => fake()->dateTimeBetween('today', '+3 months')->format('Y-m-d'),
            ]);
        }
    }

    private function buildHourWeights(): array
    {
        $weights = [];

        foreach (range(0, 23) as $hour) {
            $weight = match (true) {
                $hour === 6 || $hour === 7 => 20,
                $hour >= 8 && $hour <= 10 => 10,
                $hour >= 11 && $hour <= 14 => 14,
                $hour === 15 || $hour === 16 => 10,
                $hour >= 17 && $hour <= 20 => 42,
                $hour === 21 => 18,
                $hour === 22 => 8,
                default => 3,
            };

            foreach (range(1, $weight) as $i) {
                $weights[] = $hour;
            }
        }

        return $weights;
    }

    private function weightedHour(): int
    {
        return $this->hourWeights[array_rand($this->hourWeights)];
    }
}
