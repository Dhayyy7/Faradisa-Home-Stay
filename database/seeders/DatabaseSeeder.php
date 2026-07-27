<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Room;
use App\Models\Facility;
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
        // Seed default roles
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

        // Seed Admin user
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

        // Seed master facilities
        $facilities = [
            ['name' => 'AC (Air Conditioner)', 'icon' => 'fa-snowflake', 'description' => 'Pendingin ruangan dingin & sejuk.'],
            ['name' => 'Wi-Fi Gratis 100Mbps', 'icon' => 'fa-wifi', 'description' => 'Koneksi internet cepat 24 jam.'],
            ['name' => 'Smart TV 43 Inch', 'icon' => 'fa-tv', 'description' => 'Siaran TV kabel & Netflix.'],
            ['name' => 'Pemanas Air (Water Heater)', 'icon' => 'fa-shower', 'description' => 'Mandi air hangat nyaman.'],
            ['name' => 'Dapur Mini & Kulkas', 'icon' => 'fa-utensils', 'description' => 'Fasilitas memasak ringan & pendingin minuman.'],
            ['name' => 'Sarapan Gratis', 'icon' => 'fa-mug-hot', 'description' => 'Menu sarapan lezat setiap pagi.'],
        ];

        $facilityModels = [];
        foreach ($facilities as $f) {
            $facilityModels[$f['name']] = Facility::updateOrCreate(
                ['name' => $f['name']],
                $f
            );
        }

        // Seed sample rooms
        $room1 = Room::updateOrCreate(
            ['code' => 'KMR-001'],
            [
                'code' => 'KMR-001',
                'name' => 'Deluxe Room #01',
                'price' => 500000,
                'discount' => 10.00, // 10% discount
                'images' => [],
            ]
        );
        $room1->facilities()->sync([
            $facilityModels['AC (Air Conditioner)']->id,
            $facilityModels['Wi-Fi Gratis 100Mbps']->id,
            $facilityModels['Smart TV 43 Inch']->id,
            $facilityModels['Pemanas Air (Water Heater)']->id,
        ]);

        $room2 = Room::updateOrCreate(
            ['code' => 'KMR-002'],
            [
                'code' => 'KMR-002',
                'name' => 'Family Suite #02',
                'price' => 850000,
                'discount' => 15.00, // 15% discount
                'images' => [],
            ]
        );
        $room2->facilities()->sync([
            $facilityModels['AC (Air Conditioner)']->id,
            $facilityModels['Wi-Fi Gratis 100Mbps']->id,
            $facilityModels['Smart TV 43 Inch']->id,
            $facilityModels['Pemanas Air (Water Heater)']->id,
            $facilityModels['Dapur Mini & Kulkas']->id,
            $facilityModels['Sarapan Gratis']->id,
        ]);

        $room3 = Room::updateOrCreate(
            ['code' => 'KMR-003'],
            [
                'code' => 'KMR-003',
                'name' => 'Standard Room #03',
                'price' => 350000,
                'discount' => null, // no discount
                'images' => [],
            ]
        );
        $room3->facilities()->sync([
            $facilityModels['AC (Air Conditioner)']->id,
            $facilityModels['Wi-Fi Gratis 100Mbps']->id,
        ]);
    }
}
