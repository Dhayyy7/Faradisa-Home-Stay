<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtraFacility;
use Illuminate\Http\Request;

class ExtraFacilityController extends Controller
{
    /**
     * Display a listing of extra facilities.
     */
    public function index()
    {
        $extraFacilities = ExtraFacility::latest()->get();

        return view('admin.extra-facilities.index', compact('extraFacilities'));
    }

    /**
     * Store a newly created extra facility in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama extra fasilitas wajib diisi.',
            'price.required' => 'Harga extra fasilitas wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
        ]);

        ExtraFacility::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.extra-facilities.index')->with('success', 'Extra Fasilitas baru berhasil ditambahkan!');
    }

    /**
     * Update the specified extra facility in storage.
     */
    public function update(Request $request, ExtraFacility $extraFacility)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama extra fasilitas wajib diisi.',
            'price.required' => 'Harga extra fasilitas wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
        ]);

        $extraFacility->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.extra-facilities.index')->with('success', 'Data extra fasilitas berhasil diperbarui!');
    }

    /**
     * Remove the specified extra facility from storage (Soft Delete).
     */
    public function destroy(ExtraFacility $extraFacility)
    {
        $extraFacility->delete();

        return redirect()->route('admin.extra-facilities.index')->with('success', 'Extra fasilitas berhasil dihapus (Soft Delete)!');
    }
}
