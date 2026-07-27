@extends('admin.layouts.app')

@section('title', 'Extra Fasilitas')
@section('page_title', 'Pengelolaan Extra Fasilitas Homestay')

@section('styles')
<style>
    /* Action Buttons Vertical Layout */
    .action-btns {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0.35rem;
        width: 100%;
        max-width: 90px;
        margin: 0 auto;
    }

    .action-btns form {
        width: 100%;
        margin: 0;
    }

    .btn-edit {
        background-color: #e0e7ff;
        color: #4338ca;
        border: none;
        padding: 0.35rem 0.6rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-edit:hover {
        background-color: #c7d2fe;
    }

    .btn-delete {
        background-color: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 0.35rem 0.6rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-delete:hover {
        background-color: #fca5a5;
    }

    /* Modal Backdrop & Card */
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
        max-width: 520px;
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transform: translateY(20px);
        transition: transform 0.25s ease;
        max-height: 90vh;
        overflow-y: auto;
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

<!-- Card Full Width Tabel Extra Fasilitas -->
<div class="card" style="width: 100%;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h2 class="card-title" style="margin-bottom: 0;">
            <i class="fa-solid fa-square-plus" style="color: #4f46e5;"></i>
            Daftar Extra Fasilitas Homestay
        </h2>
        <button type="button" class="btn-submit" onclick="openCreateExtraFacilityModal()">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Tambah Extra Fasilitas</span>
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Extra Fasilitas</th>
                    <th>Harga Biaya (Rp)</th>
                    <th>Deskripsi</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($extraFacilities as $index => $ef)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: 700; color: #0f172a;">{{ $ef->name }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #16a34a;">Rp {{ number_format($ef->price, 0, ',', '.') }}</div>
                    </td>
                    <td>
                        <span style="font-size: 0.85rem; color: #475569;">{{ $ef->description ?? '-' }}</span>
                    </td>
                    <td style="text-align: center;">
                        <div class="action-btns">
                            <button type="button" class="btn-edit" onclick="openEditExtraFacilityModal({{ $ef->id }}, '{{ addslashes($ef->name) }}', {{ $ef->price }}, '{{ addslashes($ef->description ?? '') }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit</span>
                            </button>

                            <form action="{{ route('admin.extra-facilities.destroy', $ef->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="confirmDelete(this, 'Apakah Anda yakin ingin menghapus extra fasilitas {{ $ef->name }}?')">
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

<!-- Modal Partials -->
@include('admin.extra-facilities.modals.create')
@include('admin.extra-facilities.modals.edit')

@endsection

@section('scripts')
@include('admin.extra-facilities.scripts')
@endsection
