<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Services\HolidayService;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Store a newly created custom holiday / special rate date.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date', 'unique:holidays,date'],
            'name' => ['required', 'string', 'max:255'],
        ], [
            'date.required' => 'Tanggal wajib diisi.',
            'date.unique' => 'Tanggal tersebut sudah terdaftar dalam tanggal merah/libur.',
            'name.required' => 'Keterangan tanggal libur wajib diisi.',
        ]);

        $holiday = Holiday::create([
            'date' => $request->input('date'),
            'name' => $request->input('name'),
            'is_national_holiday' => true,
        ]);

        // Clear holiday cache for the year
        $year = (int) date('Y', strtotime($holiday->date));
        app(HolidayService::class)->clearCache($year);

        return redirect()->back()->with('success', 'Tanggal libur/rate weekend khusus berhasil ditambahkan!');
    }

    /**
     * Remove the specified holiday date.
     */
    public function destroy(Holiday $holiday)
    {
        $year = (int) date('Y', strtotime($holiday->date));
        $holiday->delete();

        // Clear holiday cache for the year
        app(HolidayService::class)->clearCache($year);

        return redirect()->back()->with('success', 'Tanggal libur/rate weekend khusus berhasil dihapus!');
    }
}
