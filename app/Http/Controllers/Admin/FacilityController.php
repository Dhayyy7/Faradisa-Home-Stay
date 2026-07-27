<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of facilities.
     */
    public function index()
    {
        $facilities = Facility::latest()->get();

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Store a newly created facility in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name'],
            'icon' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama fasilitas wajib diisi.',
            'name.unique' => 'Nama fasilitas sudah terdaftar.',
        ]);

        Facility::create([
            'name' => $request->input('name'),
            'icon' => $request->input('icon') ?? 'fa-check-circle',
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas baru berhasil ditambahkan!');
    }

    /**
     * Update the specified facility in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name,' . $facility->id],
            'icon' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama fasilitas wajib diisi.',
            'name.unique' => 'Nama fasilitas sudah terdaftar.',
        ]);

        $facility->update([
            'name' => $request->input('name'),
            'icon' => $request->input('icon') ?? 'fa-check-circle',
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Data fasilitas berhasil diperbarui!');
    }

    /**
     * Remove the specified facility from storage (Soft Delete).
     */
    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus (Soft Delete)!');
    }
}
