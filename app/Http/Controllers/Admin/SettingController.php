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
            'address' => ['nullable', 'string', 'max:1000'],
            'gmap_link' => ['nullable', 'string', 'max:1000'],
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
        $address = $request->input('address');
        $gmapLink = $request->input('gmap_link');

        // Handle Logo Upload
        $logoPath = $setting->logo;
        if ($request->hasFile('logo')) {
            if ($logoPath) {
                $this->deleteUploadedFile($logoPath);
            }
            $logoPath = $this->saveUploadedFile($request->file('logo'), 'settings');
        }

        // Handle Existing Media Assets & Deletion
        $currentAssets = is_array($setting->media_assets) ? $setting->media_assets : [];
        $deleteAssets = $request->input('delete_assets', []);

        if (!empty($deleteAssets)) {
            $filteredAssets = [];
            foreach ($currentAssets as $asset) {
                if (in_array($asset['path'], $deleteAssets)) {
                    $this->deleteUploadedFile($asset['path']);
                } else {
                    $filteredAssets[] = $asset;
                }
            }
            $currentAssets = $filteredAssets;
        }

        // Handle New Media Assets Upload
        if ($request->hasFile('new_assets')) {
            $currentCount = count($currentAssets);
            foreach ($request->file('new_assets') as $file) {
                if ($file->isValid()) {
                    $currentCount++;
                    $assetKey = 'asset-' . $currentCount;
                    $extension = strtolower($file->getClientOriginalExtension());
                    $path = $this->saveUploadedFile($file, 'settings/assets');
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
            'address' => $address,
            'gmap_link' => $gmapLink,
            'logo' => $logoPath,
            'media_assets' => $reindexedAssets,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan Homestay & Media Asset berhasil diperbarui!');
    }

    /**
     * Helper to save uploaded file across all potential shared hosting public paths.
     */
    private function saveUploadedFile($file, $subfolder)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $subfolderClean = trim($subfolder, '/');
        $relativePath = 'uploads/' . $subfolderClean . '/' . $filename;

        $directories = array_unique([
            public_path('uploads/' . $subfolderClean),
            base_path('uploads/' . $subfolderClean),
            base_path('public_html/uploads/' . $subfolderClean),
            base_path('public/uploads/' . $subfolderClean),
        ]);

        $primaryDir = $directories[0];
        if (!File::exists($primaryDir)) {
            File::makeDirectory($primaryDir, 0755, true);
        }

        $file->move($primaryDir, $filename);

        foreach (array_slice($directories, 1) as $targetDir) {
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            $targetFile = $targetDir . '/' . $filename;
            if (File::exists($primaryDir . '/' . $filename) && !File::exists($targetFile)) {
                @File::copy($primaryDir . '/' . $filename, $targetFile);
            }
        }

        return $relativePath;
    }

    /**
     * Helper to delete file across all potential shared hosting public paths.
     */
    private function deleteUploadedFile($relativePath)
    {
        if (empty($relativePath)) return;
        $cleanPath = ltrim($relativePath, '/');
        $paths = array_unique([
            public_path($cleanPath),
            base_path($cleanPath),
            base_path('public_html/' . $cleanPath),
            base_path('public/' . $cleanPath),
        ]);
        foreach ($paths as $p) {
            if (File::exists($p)) {
                @File::delete($p);
            }
        }
    }
}
