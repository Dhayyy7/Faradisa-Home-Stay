<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Sample statistics data for Homestay Dashboard
        $stats = [
            'total_rooms' => 12,
            'available_rooms' => 8,
            'active_bookings' => 4,
            'monthly_revenue' => 14500000,
            'occupancy_rate' => 67,
        ];

        $recentBookings = [
            [
                'id' => 'HS-1092',
                'guest_name' => 'Budi Santoso',
                'room_type' => 'Deluxe Room #02',
                'check_in' => '2026-07-26',
                'check_out' => '2026-07-28',
                'status' => 'Confirmed',
                'total' => 'Rp 850.000',
            ],
            [
                'id' => 'HS-1091',
                'guest_name' => 'Siti Rahma',
                'room_type' => 'Family Suite #05',
                'check_in' => '2026-07-27',
                'check_out' => '2026-07-30',
                'status' => 'Pending',
                'total' => 'Rp 1.600.000',
            ],
            [
                'id' => 'HS-1090',
                'guest_name' => 'Ahmad Hidayat',
                'room_type' => 'Standard Room #01',
                'check_in' => '2026-07-25',
                'check_out' => '2026-07-27',
                'status' => 'Checked In',
                'total' => 'Rp 650.000',
            ],
            [
                'id' => 'HS-1089',
                'guest_name' => 'Dewi Lestari',
                'room_type' => 'Superior Room #03',
                'check_in' => '2026-07-24',
                'check_out' => '2026-07-25',
                'status' => 'Completed',
                'total' => 'Rp 750.000',
            ],
        ];

        return view('admin.dashboard.index', compact('stats', 'recentBookings'));
    }
}
