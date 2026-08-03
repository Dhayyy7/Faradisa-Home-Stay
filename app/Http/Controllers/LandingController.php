<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ExtraFacility;
use App\Models\Room;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        $setting = Setting::getSetting();

        $rooms = Room::with('facilities')->latest()->get();
        $extraFacilities = ExtraFacility::all();

        return view('landing.index', compact('setting', 'rooms', 'extraFacilities'));
    }

    /**
     * Display the room booking detail page.
     */
    public function booking(Room $room)
    {
        $room->load(['facilities', 'bookings']);

        $setting = Setting::getSetting();

        $extraFacilities = ExtraFacility::all();

        // Calculate booked dates for availability calendar
        $bookedDates = [];
        foreach ($room->bookings as $b) {
            if (in_array($b->status, [1, 2, 4]) && $b->check_in_date && $b->check_out_date) {
                $curr = Carbon::parse($b->check_in_date);
                $end = Carbon::parse($b->check_out_date);
                while ($curr < $end) {
                    $bookedDates[] = $curr->format('Y-m-d');
                    $curr->addDay();
                }
            }
        }

        return view('landing.booking', compact('setting', 'room', 'extraFacilities', 'bookedDates'));
    }

    /**
     * Store new booking from landing/booking modal (Status 1: Pending).
     */
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_address' => ['nullable', 'string', 'max:255'],
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'extra_facility_ids' => ['nullable', 'array'],
            'extra_facility_ids.*' => ['exists:extra_facilities,id'],
        ]);

        $roomId = $validated['room_id'];
        $checkInStr = $validated['check_in_date'];
        $checkOutStr = $validated['check_out_date'];

        // Check availability conflict with active bookings (status 1, 2, 4)
        $isConflict = Booking::where('room_id', $roomId)
            ->whereIn('status', [1, 2, 4])
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
            })
            ->where(function ($q) use ($checkInStr, $checkOutStr) {
                $q->where('check_in_date', '<', $checkOutStr)
                  ->where('check_out_date', '>', $checkInStr);
            })
            ->exists();

        if ($isConflict) {
            return response()->json([
                'success' => false,
                'message' => 'Kamar sudah terbooking pada tanggal yang dipilih. Silakan pilih tanggal lain.'
            ], 422);
        }

        $room = Room::findOrFail($roomId);
        $checkIn = Carbon::parse($checkInStr);
        $checkOut = Carbon::parse($checkOutStr);

        $totalNights = max(1, $checkIn->diffInDays($checkOut));

        // Generate Booking Code: RoomCode + YYYYMMDD
        $baseCode = strtoupper($room->code) . $checkIn->format('Ymd');
        $bookingCode = $baseCode;
        $counter = 1;
        while (Booking::where('booking_code', $bookingCode)->exists()) {
            $bookingCode = $baseCode . '-' . $counter;
            $counter++;
        }

        $roomPrice = $room->price;
        $discount = $room->discount;
        $finalPricePerNight = $room->final_price;
        $roomTotalPrice = $finalPricePerNight * $totalNights;

        // Process Extra Facilities
        $selectedExtraIds = $request->input('extra_facility_ids', []);
        $extraFacilitiesList = ExtraFacility::whereIn('id', $selectedExtraIds)->get();

        $totalExtraPrice = 0;
        $savedExtraFacilities = [];
        foreach ($extraFacilitiesList as $ef) {
            $totalExtraPrice += $ef->price;
            $savedExtraFacilities[] = [
                'id' => $ef->id,
                'name' => $ef->name,
                'price' => (float) $ef->price,
            ];
        }

        $totalPrice = $roomTotalPrice + $totalExtraPrice;

        // Status 1 = Pending, Expired in 2 hours
        $booking = Booking::create([
            'room_id' => $room->id,
            'booking_code' => $bookingCode,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'] ?? 'Banjarbaru',
            'check_in_date' => $checkIn->format('Y-m-d'),
            'check_out_date' => $checkOut->format('Y-m-d'),
            'total_nights' => $totalNights,
            'room_price' => $roomPrice,
            'discount' => $discount,
            'total_price' => $totalPrice,
            'status' => 1,
            'expired_at' => Carbon::now()->addHours(2),
            'extra_facilities' => $savedExtraFacilities,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dicatat dengan status Pending',
            'booking' => [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'customer_name' => $booking->customer_name,
                'customer_phone' => $booking->customer_phone,
                'room_name' => $room->name,
                'room_code' => $room->code,
                'check_in_date' => $checkIn->format('Y-m-d'),
                'check_out_date' => $checkOut->format('Y-m-d'),
                'total_nights' => $totalNights,
                'total_price' => $totalPrice,
                'extra_facilities' => $savedExtraFacilities,
            ]
        ]);
    }
}
