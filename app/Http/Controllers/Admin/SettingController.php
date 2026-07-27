<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $setting = Setting::getSetting();

        return view('admin.settings.index', compact('setting'));
    }

    /**
     * Update homestay settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'homestay_name' => ['required', 'string', 'max:255'],
            'wa_number' => ['required', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'new_assets.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,mp4,webm,mov,avi', 'max:20480'],
            'delete_assets' => ['nullable', 'array'],
        ], [
            'homestay_name.required' => 'Nama Homestay wajib diisi.',
            'wa_number.required' => 'Nomor WhatsApp wajib diisi.',
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.max' => 'Ukuran file logo maksimal 2MB.',
            'new_assets.*.mimes' => 'Format file asset media harus berupa Foto (JPG, PNG, WEBP) atau Video (MP4, WEBM, MOV).',
            'new_assets.*.max' => 'Ukuran file asset media maksimal 20MB per file.',
        ]);

        $setting = Setting::getSetting();

        $homestayName = $request->input('homestay_name');
        $waNumber = $request->input('wa_number');

        // Handle Logo Upload
        $logoPath = $setting->logo;
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($logoPath && File::exists(public_path($logoPath))) {
                File::delete(public_path($logoPath));
            }

            $logoFile = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads/settings');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $logoFile->move($destinationPath, $logoName);
            $logoPath = 'uploads/settings/' . $logoName;
        }

        // Handle Existing Media Assets & Deletion
        $currentAssets = is_array($setting->media_assets) ? $setting->media_assets : [];
        $deleteAssets = $request->input('delete_assets', []);

        if (!empty($deleteAssets)) {
            $filteredAssets = [];
            foreach ($currentAssets as $asset) {
                if (in_array($asset['path'], $deleteAssets)) {
                    if (File::exists(public_path($asset['path']))) {
                        File::delete(public_path($asset['path']));
                    }
                } else {
                    $filteredAssets[] = $asset;
                }
            }
            $currentAssets = $filteredAssets;
        }

        // Handle New Media Assets Upload
        if ($request->hasFile('new_assets')) {
            $assetDestination = public_path('uploads/settings/assets');
            if (!File::exists($assetDestination)) {
                File::makeDirectory($assetDestination, 0755, true);
            }

            $currentCount = count($currentAssets);
            foreach ($request->file('new_assets') as $file) {
                if ($file->isValid()) {
                    $currentCount++;
                    $assetKey = 'asset-' . $currentCount;
                    $extension = strtolower($file->getClientOriginalExtension());
                    $assetName = $assetKey . '_' . time() . '.' . $extension;
                    $file->move($assetDestination, $assetName);

                    $path = 'uploads/settings/assets/' . $assetName;
                    $type = in_array($extension, ['mp4', 'webm', 'mov', 'avi']) ? 'video' : 'image';

                    $currentAssets[] = [
                        'key' => $assetKey,
                        'path' => $path,
                        'type' => $type,
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }
        }

        // Re-index all keys sequentially (asset-1, asset-2, asset-3...)
        $reindexedAssets = [];
        foreach (array_values($currentAssets) as $idx => $asset) {
            $assetKey = 'asset-' . ($idx + 1);
            $asset['key'] = $assetKey;
            $reindexedAssets[] = $asset;
        }

        $setting->update([
            'homestay_name' => $homestayName,
            'wa_number' => $waNumber,
            'logo' => $logoPath,
            'media_assets' => $reindexedAssets,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan Homestay & Media Asset berhasil diperbarui!');
    }
}
