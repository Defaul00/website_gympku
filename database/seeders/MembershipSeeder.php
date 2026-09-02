<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $memberships = [
            ['name' => '1 Bulan', 'duration_months' => 1, 'price' => 150000, 'description' => 'Akses penuh fasilitas gym selama 1 bulan.', 'features' => ['Akses semua fasilitas', 'Group classes', 'Locker & shower']],
            ['name' => '3 Bulan', 'duration_months' => 3, 'price' => 425000, 'description' => 'Paket populer dengan bonus konsultasi nutrisi.', 'features' => ['Akses semua fasilitas', 'Group classes', '1x konsultasi nutrisi', 'Locker & shower']],
            ['name' => '6 Bulan', 'duration_months' => 6, 'price' => 800000, 'description' => 'Komitmen setengah tahun dengan harga terbaik.', 'features' => ['Akses semua fasilitas', 'Group classes', '2x konsultasi nutrisi', '1x personal training', 'Locker & shower']],
            ['name' => '12 Bulan', 'duration_months' => 12, 'price' => 1600000, 'description' => 'Paket tahunan paling hemat untuk member setia.', 'features' => ['Akses semua fasilitas', 'Group classes', 'Konsultasi nutrisi unlimited', '4x personal training', 'Free merchandise', 'Locker & shower']],
        ];

        foreach ($memberships as $membership) {
            Membership::query()->updateOrCreate(['name' => $membership['name']], $membership);
        }
    }
}
