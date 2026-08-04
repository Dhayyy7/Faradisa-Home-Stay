<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms.
     */
    public function index()
    {
        $rooms = Room::with('facilities')->latest()->get();
        $facilities = Facility::all();

        return view('admin.rooms.index', compact('rooms', 'facilities'));
    }

    /**
     * Display room details with interactive availability calendar.
     */
    public function details(Request $request)
    {
        $rooms = Room::with(['facilities', 'bookings' => function($q) {
            $q->whereIn('status', [1, 2, 4]);
        }])->latest()->get();

        $calYear = (int) $request->input('cal_year', date('Y'));
        $holidayService = app(\App\Services\HolidayService::class);
        $holidayDates = $holidayService->getHolidayDates($calYear);
        $allHolidays = \App\Models\Holiday::orderBy('date', 'desc')->get();

        return view('admin.rooms.details', compact('rooms', 'holidayDates', 'allHolidays'));
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:rooms,code'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'weekend_price' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['exists:facilities,id'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'code.required' => 'Kode Kamar wajib diisi.',
            'code.unique' => 'Kode Kamar sudah terdaftar.',
            'name.required' => 'Nama Kamar / Unit wajib diisi.',
            'price.required' => 'Harga kamar wajib diisi.',
            'discount.max' => 'Diskon maksimal 100%.',
            'images.max' => 'Maksimal 10 foto kamar yang dapat diunggah.',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/rooms');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $imagePaths[] = 'uploads/rooms/' . $filename;
            }
        }

        $room = Room::create([
            'code' => strtoupper($request->input('code')),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'weekend_price' => $request->input('weekend_price'),
            'discount' => $request->input('discount'),
            'description' => $request->input('description'),
            'images' => $imagePaths,
        ]);

        if ($request->has('facilities')) {
            $room->facilities()->sync($request->input('facilities'));
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar / Unit baru berhasil ditambahkan!');
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('rooms', 'code')->ignore($room->id)],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'weekend_price' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['exists:facilities,id'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'deleted_images' => ['nullable', 'array'],
        ], [
            'code.required' => 'Kode Kamar wajib diisi.',
            'code.unique' => 'Kode Kamar sudah terdaftar.',
            'name.required' => 'Nama Kamar / Unit wajib diisi.',
            'price.required' => 'Harga kamar wajib diisi.',
            'discount.max' => 'Diskon maksimal 100%.',
            'images.max' => 'Maksimal 10 foto kamar yang dapat diunggah.',
        ]);

        $imagePaths = is_array($room->images) ? array_values($room->images) : [];

        // Process deleted images
        if ($request->has('deleted_images')) {
            $deletedImages = $request->input('deleted_images', []);
            foreach ($deletedImages as $delPath) {
                $fullPath = public_path($delPath);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
                $imagePaths = array_values(array_filter($imagePaths, fn($p) => $p !== $delPath));
            }
        }

        // Process newly uploaded images
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/rooms');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $imagePaths[] = 'uploads/rooms/' . $filename;
            }
        }

        // Keep up to 5 images
        $imagePaths = array_slice(array_values($imagePaths), 0, 5);

        $room->update([
            'code' => strtoupper($request->input('code')),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'weekend_price' => $request->input('weekend_price'),
            'discount' => $request->input('discount'),
            'description' => $request->input('description'),
            'images' => $imagePaths,
        ]);

        $room->facilities()->sync($request->input('facilities', []));

        return redirect()->route('admin.rooms.index')->with('success', 'Data kamar berhasil diperbarui!');
    }

    /**
     * Remove the specified room from storage (Soft Delete).
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Data kamar berhasil dihapus (Soft Delete)!');
    }
}
