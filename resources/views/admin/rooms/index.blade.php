@extends('admin.layouts.app')

@section('title', 'Kamar & Unit')
@section('page_title', 'Pengelolaan Kamar & Unit')

@section('styles')
<style>
    /* Checkbox Group for Select to Many Facilities */
    .facility-checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.6rem;
        background: #f8fafc;
        padding: 0.875rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        max-height: 180px;
        overflow-y: auto;
    }

    .facility-checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.825rem;
        color: #334155;
        cursor: pointer;
    }

    .facility-checkbox-item input[type="checkbox"] {
        accent-color: #4f46e5;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .badge-code {
        background-color: #e0e7ff;
        color: #4338ca;
        padding: 0.15rem 0.5rem;
        border-radius: 6px;
        font-family: monospace;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .badge-discount-percent {
        background-color: #fee2e2;
        color: #dc2626;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .facility-badge-pill {
        background-color: #f1f5f9;
        color: #475569;
        padding: 0.15rem 0.45rem;
        border-radius: 6px;
        font-size: 0.75rem;
        display: inline-block;
        margin: 0.15rem 0.1rem;
    }

    /* Action Buttons Vertical Layout */
    .action-btns {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0.35rem;
        width: 100%;
        max-width: 100px;
        margin: 0 auto;
    }

    .action-btns form {
        width: 100%;
        margin: 0;
    }

    .btn-preview {
        background-color: #ccfbf1;
        color: #0f766e;
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

    .btn-preview:hover {
        background-color: #99f6e4;
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
        max-width: 580px;
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

<!-- Card Full Width Tabel Daftar Kamar -->
<div class="card" style="width: 100%;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h2 class="card-title" style="margin-bottom: 0;">
            <i class="fa-solid fa-bed" style="color: #4f46e5;"></i>
            Daftar Kamar & Unit Terdaftar
        </h2>
        <button type="button" class="btn-submit" onclick="openCreateRoomModal()">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Tambah Kamar / Unit</span>
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Kode & Nama Kamar</th>
                    <th>Harga Normal</th>
                    <th>Diskon</th>
                    <th>Fasilitas</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $index => $room)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @php
                            $roomImages = is_array($room->images) ? $room->images : [];
                            $lastImage = count($roomImages) > 0 ? end($roomImages) : null;
                        @endphp
                        @if($lastImage)
                            <img src="/{{ $lastImage }}" alt="{{ $room->name }}" style="width: 55px; height: 55px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        @else
                            <div style="width: 55px; height: 55px; border-radius: 10px; background-color: #f1f5f9; border: 1px dashed #cbd5e1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; font-size: 0.68rem; text-align: center;">
                                <i class="fa-solid fa-image" style="font-size: 0.9rem; margin-bottom: 0.15rem; color: #cbd5e1;"></i>
                                No Image
                            </div>
                        @endif
                    </td>
                    <td>
                        <div><span class="badge-code">{{ $room->code }}</span></div>
                        <div style="font-weight: 700; color: #1e293b; margin-top: 0.25rem;">{{ $room->name }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #0f172a;">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                        @if($room->discount && $room->discount > 0)
                        <div style="font-size: 0.78rem; color: #16a34a; font-weight: 600;">
                            Nett: Rp {{ number_format($room->final_price, 0, ',', '.') }}
                        </div>
                        @endif
                    </td>
                    <td>
                        @if($room->discount && $room->discount > 0)
                        <span class="badge-discount-percent">{{ number_format($room->discount, 0) }}% OFF</span>
                        @else
                        <span style="color: #94a3b8; font-style: italic;">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        @forelse($room->facilities as $f)
                        <span class="facility-badge-pill">
                            <i class="fa-solid {{ $f->icon ?? 'fa-check' }}" style="font-size: 0.7rem; color: #4f46e5;"></i>
                            {{ $f->name }}
                        </span>
                        @empty
                        <span style="color: #94a3b8; font-style: italic;">Belum ada fasilitas</span>
                        @endforelse
                    </td>
                    <td style="text-align: center;">
                        <div class="action-btns">
                            <button type="button" class="btn-preview" onclick="openPreviewModal({{ json_encode($room) }}, {{ json_encode($room->facilities) }}, {{ $room->final_price }})">
                                <i class="fa-solid fa-eye"></i>
                                <span>Preview</span>
                            </button>

                            <button type="button" class="btn-edit" onclick="openEditModal({{ $room->id }}, '{{ addslashes($room->code) }}', '{{ addslashes($room->name) }}', {{ $room->price }}, {{ $room->discount ?? 0 }}, {{ json_encode($room->facilities->pluck('id')->toArray()) }})">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit</span>
                            </button>

                            <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="confirmDelete(this, 'Apakah Anda yakin ingin menghapus kamar {{ $room->name }}?')">
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
@include('admin.rooms.modals.create')
@include('admin.rooms.modals.edit')
@include('admin.rooms.modals.preview')

@endsection

@section('scripts')
@include('admin.rooms.scripts')
@endsection