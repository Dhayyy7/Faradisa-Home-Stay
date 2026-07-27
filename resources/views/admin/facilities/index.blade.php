@extends('admin.layouts.app')

@section('title', 'Manajemen Fasilitas')
@section('page_title', 'Master Fasilitas Homestay')

@section('styles')
<style>
    .facility-grid {
        display: grid;
        grid-template-columns: 1fr 2.2fr;
        gap: 1.5rem;
    }

    @media (max-width: 992px) {
        .facility-grid {
            grid-template-columns: 1fr;
        }
    }

    .facility-icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background-color: #e0e7ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .action-btns {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-edit {
        background-color: #e0e7ff;
        color: #4338ca;
        border: none;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s;
    }

    .btn-edit:hover {
        background-color: #c7d2fe;
    }

    .btn-delete {
        background-color: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s;
    }

    .btn-delete:hover {
        background-color: #fca5a5;
    }

    .modal-backdrop {
        position: fixed;
        inset: 0;
        background-color: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s ease;
    }

    .modal-backdrop.show {
        opacity: 1;
        visibility: visible;
    }

    .modal-card {
        background: #ffffff;
        width: 100%;
        max-width: 500px;
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transform: translateY(20px);
        transition: transform 0.25s ease;
    }

    .modal-backdrop.show .modal-card {
        transform: translateY(0);
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .modal-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
    }

    .btn-close-modal {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #64748b;
        cursor: pointer;
    }
</style>
@endsection

@section('content')

<div class="facility-grid">
    <!-- Form Tambah Fasilitas -->
    <div class="card">
        <h2 class="card-title">
            <i class="fa-solid fa-plus-circle" style="color: #4f46e5;"></i>
            Tambah Fasilitas Baru
        </h2>

        <form action="{{ route('admin.facilities.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Fasilitas</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Misal: Wi-Fi Gratis, AC, Water Heater" required>
            </div>

            <div class="form-group">
                <label for="icon" class="form-label">Icon FontAwesome <span style="font-weight: 400; color: #64748b;">(Opsional)</span></label>
                <input type="text" id="icon" name="icon" class="form-input" placeholder="Misal: fa-wifi, fa-snowflake, fa-tv">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi <span style="font-weight: 400; color: #64748b;">(Opsional)</span></label>
                <textarea id="description" name="description" rows="3" class="form-textarea" placeholder="Keterangan singkat tentang fasilitas ini..."></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-save"></i>
                <span>Simpan Fasilitas</span>
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Fasilitas -->
    <div class="card">
        <h2 class="card-title">
            <i class="fa-solid fa-list-check" style="color: #4f46e5;"></i>
            Daftar Fasilitas Terdaftar
        </h2>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Icon</th>
                        <th>Nama Fasilitas</th>
                        <th>Deskripsi</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facilities as $index => $f)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="facility-icon-badge">
                                    <i class="fa-solid {{ $f->icon ?? 'fa-check-circle' }}"></i>
                                </div>
                            </td>
                            <td style="font-weight: 700; color: #1e293b;">{{ $f->name }}</td>
                            <td style="color: #64748b; font-size: 0.85rem;">{{ $f->description ?? '-' }}</td>
                            <td style="text-align: center;">
                                <div class="action-btns" style="justify-content: center;">
                                    <button type="button" class="btn-edit" onclick="openEditModal({{ $f->id }}, '{{ addslashes($f->name) }}', '{{ addslashes($f->icon ?? '') }}', '{{ addslashes($f->description ?? '') }}')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Edit</span>
                                    </button>

                                    <form action="{{ route('admin.facilities.destroy', $f->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete" onclick="confirmDelete(this, 'Apakah Anda yakin ingin menghapus fasilitas {{ $f->name }}?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Partials -->
@include('admin.facilities.modals.edit')

@endsection

@section('scripts')
@include('admin.facilities.scripts')
@endsection
