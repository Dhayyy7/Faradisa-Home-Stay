<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard analytics.
     */
    public function index(Request $request)
    {
        // 1. Calculate Real Stat Metrics
        $totalRooms = Room::count();
        $activeBookings = Booking::whereIn('status', [1, 2, 4])->count();
        $pendingCount = Booking::where('status', 1)->count();

        $currentMonth = $request->input('month', date('m'));
        $currentYear = $request->input('year', date('Y'));

        $monthlyRevenue = Booking::whereIn('status', [2, 3, 4])
            ->whereYear('check_in_date', $currentYear)
            ->whereMonth('check_in_date', (int)$currentMonth)
            ->sum('total_price');

        // 2. Prepare Grouped Bar Chart Data for selected month
        $daysInMonth = Carbon::createFromDate((int)$currentYear, (int)$currentMonth, 1)->daysInMonth;
        $chartLabels = [];
        $chartDataLunas = [];
        $chartDataBatal = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
            $chartLabels[] = $day . ' ' . Carbon::createFromDate((int)$currentYear, (int)$currentMonth, 1)->format('M');

            $lunasCount = Booking::whereIn('status', [2, 3, 4])->whereDate('check_in_date', $dateStr)->count();
            $batalCount = Booking::where('status', 0)->whereDate('check_in_date', $dateStr)->count();

            $chartDataLunas[] = $lunasCount;
            $chartDataBatal[] = $batalCount;
        }

        // Return JSON for AJAX filter request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'labels' => $chartLabels,
                'lunas' => $chartDataLunas,
                'batal' => $chartDataBatal,
                'formatted_revenue' => 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'),
            ]);
        }

        $stats = [
            'total_rooms' => $totalRooms,
            'active_bookings' => $activeBookings,
            'monthly_revenue' => $monthlyRevenue,
            'pending_count' => $pendingCount,
        ];

        return view('admin.dashboard.index', compact(
            'stats',
            'currentMonth',
            'currentYear',
            'chartLabels',
            'chartDataLunas',
            'chartDataBatal'
        ));
    }
}
