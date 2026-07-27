<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Helper to auto-cancel expired pending bookings.
     */
    private function autoCancelExpiredBookings()
    {
        Booking::where('status', 1)
            ->where('expired_at', '<=', now())
            ->update(['status' => 0]);
    }

    /**
     * Display a listing of bookings.
     */
    public function index()
    {
        $this->autoCancelExpiredBookings();

        $bookings = Booking::with('room')->latest()->get();
        $rooms = Room::all();

        return view('admin.bookings.index', compact('bookings', 'rooms'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $this->autoCancelExpiredBookings();

        $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_address' => ['required', 'string'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_sosmed' => ['nullable', 'string', 'max:255'],
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'status' => ['nullable', 'integer', 'in:0,1,2'],
            'extra_facilities' => ['nullable', 'string'],
        ], [
            'room_id.required' => 'Pilih kamar terlebih dahulu.',
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_address.required' => 'Alamat pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor HP pemesan wajib diisi.',
            'check_in_date.required' => 'Tanggal Check-in wajib diisi.',
            'check_out_date.required' => 'Tanggal Check-out wajib diisi.',
            'check_out_date.after' => 'Tanggal Check-out harus setelah Tanggal Check-in.',
        ]);

        $roomId = $request->input('room_id');
        $checkInStr = $request->input('check_in_date');
        $checkOutStr = $request->input('check_out_date');

        // Check availability for overlaps
        $isConflict = Booking::where('room_id', $roomId)
            ->whereIn('status', [1, 2])
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
            })
            ->where(function ($q) use ($checkInStr, $checkOutStr) {
                $q->where('check_in_date', '<', $checkOutStr)
                  ->where('check_out_date', '>', $checkInStr);
            })
            ->exists();

        if ($isConflict) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kamar sudah terbooking pada tanggal tersebut.');
        }

        $room = Room::findOrFail($roomId);
        $checkIn = Carbon::parse($checkInStr);
        $checkOut = Carbon::parse($checkOutStr);

        $totalNights = max(1, $checkIn->diffInDays($checkOut));
        
        // Code format: Kode Kamar + Tanggal Check-in YYYYMMDD (e.g. P1V120260729)
        $baseCode = strtoupper($room->code) . $checkIn->format('Ymd');
        $bookingCode = $baseCode;
        $counter = 1;
        while (Booking::where('booking_code', $bookingCode)->exists()) {
            $bookingCode = $baseCode . '-' . $counter;
            $counter++;
        }

        $roomPrice = $room->price;
        $discount = $room->discount; // percent % from room if available, else null
        $finalPricePerNight = $room->final_price;
        $totalPrice = $finalPricePerNight * $totalNights;

        $status = $request->input('status', 1); // default 1 = Pending
        $expiredAt = ($status == 1) ? Carbon::now()->addHours(2) : null;

        Booking::create([
            'room_id' => $room->id,
            'booking_code' => $bookingCode,
            'customer_name' => $request->input('customer_name'),
            'customer_address' => $request->input('customer_address'),
            'customer_phone' => $request->input('customer_phone'),
            'customer_sosmed' => $request->input('customer_sosmed'),
            'check_in_date' => $checkIn->format('Y-m-d'),
            'check_out_date' => $checkOut->format('Y-m-d'),
            'total_nights' => $totalNights,
            'room_price' => $roomPrice,
            'discount' => $discount,
            'total_price' => $totalPrice,
            'status' => $status,
            'expired_at' => $expiredAt,
            'extra_facilities' => $request->input('extra_facilities'),
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Pemesanan baru berhasil disimpan dengan Kode: ' . $bookingCode);
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $this->autoCancelExpiredBookings();

        $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_address' => ['required', 'string'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_sosmed' => ['nullable', 'string', 'max:255'],
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'status' => ['required', 'integer', 'in:0,1,2'],
            'extra_facilities' => ['nullable', 'string'],
        ], [
            'room_id.required' => 'Pilih kamar terlebih dahulu.',
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_address.required' => 'Alamat pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor HP pemesan wajib diisi.',
            'check_in_date.required' => 'Tanggal Check-in wajib diisi.',
            'check_out_date.required' => 'Tanggal Check-out wajib diisi.',
            'check_out_date.after' => 'Tanggal Check-out harus setelah Tanggal Check-in.',
        ]);

        $roomId = $request->input('room_id');
        $checkInStr = $request->input('check_in_date');
        $checkOutStr = $request->input('check_out_date');

        $newStatus = (int) $request->input('status');

        // If status is active (1 or 2), check for conflicts with OTHER bookings
        if (in_array($newStatus, [1, 2])) {
            $isConflict = Booking::where('room_id', $roomId)
                ->where('id', '!=', $booking->id)
                ->whereIn('status', [1, 2])
                ->where(function ($q) {
                    $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
                })
                ->where(function ($q) use ($checkInStr, $checkOutStr) {
                    $q->where('check_in_date', '<', $checkOutStr)
                      ->where('check_out_date', '>', $checkInStr);
                })
                ->exists();

            if ($isConflict) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kamar sudah terbooking pada tanggal tersebut.');
            }
        }

        $room = Room::findOrFail($roomId);
        $checkIn = Carbon::parse($checkInStr);
        $checkOut = Carbon::parse($checkOutStr);

        $totalNights = max(1, $checkIn->diffInDays($checkOut));

        // Recalculate code if room or checkin date changed
        $baseCode = strtoupper($room->code) . $checkIn->format('Ymd');
        $bookingCode = $booking->booking_code;
        if (!str_starts_with($bookingCode, $baseCode)) {
            $bookingCode = $baseCode;
            $counter = 1;
            while (Booking::where('booking_code', $bookingCode)->where('id', '!=', $booking->id)->exists()) {
                $bookingCode = $baseCode . '-' . $counter;
                $counter++;
            }
        }

        $roomPrice = $room->price;
        $discount = $room->discount;
        $finalPricePerNight = $room->final_price;
        $totalPrice = $finalPricePerNight * $totalNights;

        // Manage expired_at: if changing to 1 (pending), extend 2 hours. If 2 (lunas) or 0 (batal), clear expired_at
        $expiredAt = $booking->expired_at;
        if ($newStatus == 1 && $booking->status != 1) {
            $expiredAt = Carbon::now()->addHours(2); // Total Jam Pending
        } elseif ($newStatus != 1) {
            $expiredAt = null;
        }

        $booking->update([
            'room_id' => $room->id,
            'booking_code' => $bookingCode,
            'customer_name' => $request->input('customer_name'),
            'customer_address' => $request->input('customer_address'),
            'customer_phone' => $request->input('customer_phone'),
            'customer_sosmed' => $request->input('customer_sosmed'),
            'check_in_date' => $checkIn->format('Y-m-d'),
            'check_out_date' => $checkOut->format('Y-m-d'),
            'total_nights' => $totalNights,
            'room_price' => $roomPrice,
            'discount' => $discount,
            'total_price' => $totalPrice,
            'status' => $newStatus,
            'expired_at' => $expiredAt,
            'extra_facilities' => $request->input('extra_facilities'),
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Data pemesanan berhasil diperbarui!');
    }

    /**
     * Remove the specified booking from storage (Soft Delete).
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Data pemesanan berhasil dihapus (Soft Delete)!');
    }
}
