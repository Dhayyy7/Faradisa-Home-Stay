@extends('admin.layouts.app')

@section('title', 'Pengaturan Homestay')
@section('page_title', 'Pengaturan Homestay & Landing Page Assets')

@section('styles')
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 1.5rem;
    }

    @media (max-width: 992px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }

    .asset-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .asset-item-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .asset-item-card img, .asset-item-card video {
        width: 100%;
        height: 110px;
        object-fit: cover;
        display: block;
    }

    .asset-delete-overlay {
        padding: 0.5rem;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
    }

    .asset-delete-checkbox {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        color: #dc2626;
        font-weight: 600;
        cursor: pointer;
    }

    .asset-delete-checkbox input[type="checkbox"] {
        accent-color: #dc2626;
        width: 15px;
        height: 15px;
    }

    .dynamic-asset-input-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .btn-remove-row {
        background-color: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 0.6rem 0.8rem;
        border-radius: 8px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-remove-row:hover {
        background-color: #fca5a5;
    }

    .btn-add-asset {
        background-color: #e0e7ff;
        color: #4338ca;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }

    .btn-add-asset:hover {
        background-color: #c7d2fe;
    }
</style>
@endsection

@section('content')

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="settings-grid">
        <!-- Card Left: Informasi Utama Homestay -->
        <div class="card">
            <h2 class="card-title">
                <i class="fa-solid fa-sliders" style="color: #4f46e5;"></i>
                Informasi Utama Homestay
            </h2>

            <!-- Logo Upload -->
            <div class="form-group">
                <label for="logo" class="form-label">Logo Homestay</label>
                
                @if($setting->logo && file_exists(public_path($setting->logo)))
                    <div style="margin-bottom: 0.85rem; padding: 0.75rem; background-color: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: inline-block;">
                        <img src="/{{ $setting->logo }}" alt="Logo Homestay" style="max-height: 70px; max-width: 180px; object-fit: contain;">
                    </div>
                @endif

                <input type="file" id="logo" name="logo" class="form-input" accept="image/*">
                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.3rem;">
                    Format: PNG, JPG, WEBP, SVG (Maks. 2MB).
                </div>
            </div>

            <!-- Nama Homestay -->
            <div class="form-group">
                <label for="homestay_name" class="form-label">Nama Homestay</label>
                <input type="text" id="homestay_name" name="homestay_name" class="form-input" value="{{ old('homestay_name', $setting->homestay_name) }}" placeholder="Masukan Nama Homestay" required>
            </div>

            <!-- Nomor WhatsApp -->
            <div class="form-group">
                <label for="wa_number" class="form-label">Nomor WhatsApp Kontak</label>
                <input type="text" id="wa_number" name="wa_number" class="form-input" value="{{ old('wa_number', $setting->wa_number) }}" placeholder="Misal: 081234567890" required>
                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.3rem;">
                    Nomor WhatsApp ini digunakan untuk menerima konfirmasi reservasi dari tamu.
                </div>
            </div>

            <button type="submit" class="btn-submit" style="width: 100%; justify-content: center; margin-top: 1rem;">
                <i class="fa-solid fa-save"></i>
                <span>Simpan Pengaturan</span>
            </button>
        </div>

        <!-- Card Right: Asset Foto & Video Landing Page -->
        <div class="card">
            <h2 class="card-title">
                <i class="fa-solid fa-photo-film" style="color: #4f46e5;"></i>
                Media Assets Landing Page (Foto & Video)
            </h2>

            <!-- Asset Upload Section -->
            <div class="form-group">
                <label class="form-label">Tambah Media Asset Baru (Foto / Video)</label>
                <div id="dynamic_asset_inputs">
                    <div class="dynamic-asset-input-row">
                        <input type="file" name="new_assets[]" class="form-input" accept="image/*,video/*">
                        <button type="button" class="btn-remove-row" onclick="removeAssetRow(this)">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>

                <button type="button" class="btn-add-asset" onclick="addAssetRow()">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Tambah File Asset Lain</span>
                </button>

                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.5rem;">
                    Mendukung Foto (JPG, PNG, WEBP) & Video (MP4, WEBM). Maksimal 20MB per file.
                </div>
            </div>

            <!-- Existing Assets Gallery Manager -->
            <div style="margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1.25rem;">
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #0f172a;">
                    Daftar Media Asset Tersimpan ({{ is_array($setting->media_assets) ? count($setting->media_assets) : 0 }})
                </h3>

                @if(is_array($setting->media_assets) && count($setting->media_assets) > 0)
                    <div class="asset-gallery-grid">
                        @foreach($setting->media_assets as $index => $asset)
                        <div class="asset-item-card">
                            <div style="position: absolute; top: 6px; left: 6px; background-color: rgba(15, 23, 42, 0.85); backdrop-filter: blur(4px); color: #ffffff; padding: 0.15rem 0.45rem; border-radius: 6px; font-family: monospace; font-size: 0.72rem; font-weight: 700; z-index: 2;">
                                {{ $asset['key'] ?? ('asset-' . ($index + 1)) }}
                            </div>
                            @if(isset($asset['type']) && $asset['type'] === 'video')
                                <video src="/{{ $asset['path'] }}" controls muted></video>
                            @else
                                <img src="/{{ $asset['path'] }}" alt="Media Asset">
                            @endif

                            <div class="asset-delete-overlay">
                                <span style="font-size: 0.7rem; font-weight: 700; color: #4338ca; text-transform: uppercase;">
                                    {{ $asset['type'] ?? 'Foto' }}
                                </span>
                                <label class="asset-delete-checkbox">
                                    <input type="checkbox" name="delete_assets[]" value="{{ $asset['path'] }}">
                                    <span>Hapus</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.6rem; font-style: italic;">
                        * Centang "Hapus" pada asset yang ingin dibuang, lalu klik "Simpan Pengaturan".
                    </div>
                @else
                    <div style="padding: 1.5rem; text-align: center; background-color: #f8fafc; border-radius: 12px; color: #94a3b8; font-size: 0.85rem; border: 1px dashed #cbd5e1; margin-top: 0.75rem;">
                        <i class="fa-solid fa-photo-film" style="font-size: 1.75rem; margin-bottom: 0.5rem; display: block; color: #cbd5e1;"></i>
                        Belum ada media asset foto / video yang diunggah.
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
    function addAssetRow() {
        const container = document.getElementById('dynamic_asset_inputs');
        const newRow = document.createElement('div');
        newRow.className = 'dynamic-asset-input-row';
        newRow.innerHTML = `
            <input type="file" name="new_assets[]" class="form-input" accept="image/*,video/*">
            <button type="button" class="btn-remove-row" onclick="removeAssetRow(this)">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        `;
        container.appendChild(newRow);
    }

    function removeAssetRow(btn) {
        const container = document.getElementById('dynamic_asset_inputs');
        if (container.children.length > 1) {
            btn.closest('.dynamic-asset-input-row').remove();
        } else {
            btn.closest('.dynamic-asset-input-row').querySelector('input').value = '';
        }
    }
</script>
@endsection
