<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Default Roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Akses penuh ke seluruh sistem dan konfigurasi homestay.',
            ],
            [
                'name' => 'Admin Homestay',
                'slug' => 'admin',
                'description' => 'Pengelolaan kamar, pemesanan, dan transaksi homestay.',
            ],
            [
                'name' => 'Staf Resepsionis',
                'slug' => 'staff',
                'description' => 'Pengelolaan reservasi, check-in, dan pelayanan tamu.',
            ],
            [
                'name' => 'Tamu',
                'slug' => 'guest',
                'description' => 'Role dasar untuk pengunjung / pelanggan homestay.',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }

        // 2. Seed Super Admin User
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Faradisa',
                'username' => 'admin',
                'email' => 'admin@faradisahomestay.com',
                'password' => Hash::make('password123'),
                'role_user' => 'Super Admin',
            ]
        );

        // 3. Seed Default Homestay Settings
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'logo' => null,
                'homestay_name' => 'Faradisa HomeStay',
                'wa_number' => '081234567890',
                'address' => 'Komplek Green Tasbih Jalan Tawaf, IX Kel No.2, RT.06/RW.04, Loktabat Selatan, Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714',
                'gmap_link' => 'https://maps.app.goo.gl/EJES9oxcGavzTXEs7',
                'media_assets' => [],
            ]
        );
    }
}
