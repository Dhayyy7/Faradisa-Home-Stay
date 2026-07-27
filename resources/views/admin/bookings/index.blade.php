@extends('admin.layouts.app')

@section('title', 'Pemesanan Kamar')
@section('page_title', 'Pengelolaan Pemesanan Homestay')

@section('styles')
<style>
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

    .status-pill {
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .status-pill.status-pending {
        background-color: #fef3c7;
        color: #b45309;
    }

    .status-pill.status-lunas {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-pill.status-batal {
        background-color: #fee2e2;
        color: #991b1b;
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

<!-- Card Full Width Tabel Daftar Pemesanan -->
<div class="card" style="width: 100%;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h2 class="card-title" style="margin-bottom: 0;">
            <i class="fa-solid fa-list-check" style="color: #4f46e5;"></i>
            Daftar Pemesanan Homestay
        </h2>
        <button type="button" class="btn-submit" onclick="openCreateBookingModal()">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Input Pemesanan Baru</span>
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode & Kamar</th>
                    <th>Pemesan</th>
                    <th>Check-In / Out</th>
                    <th>Status</th>
                    <th>Total Biaya</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $index => $b)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div><span class="badge-code">{{ $b->booking_code }}</span></div>
                        <div style="font-weight: 700; color: #1e293b; margin-top: 0.25rem;">
                            {{ $b->room->name ?? 'Kamar' }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #0f172a;">{{ $b->customer_name }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.1rem;">
                            <i class="fa-solid fa-phone" style="font-size: 0.7rem;"></i> {{ $b->customer_phone }}
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 0.85rem; font-weight: 600; color: #334155;">
                            {{ $b->check_in_date ? $b->check_in_date->format('d M Y') : '-' }}
                        </div>
                        <div style="font-size: 0.78rem; color: #64748b;">
                            s/d {{ $b->check_out_date ? $b->check_out_date->format('d M Y') : '-' }} ({{ $b->total_nights }} mlm)
                        </div>
                    </td>
                    <td>
                        @php $badge = $b->status_badge; @endphp
                        <span class="status-pill" style="background-color: {{ $badge['bg'] }}; color: {{ $badge['color'] }};">
                            <i class="fa-solid {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                        </span>
                        @if($b->status == 1 && $b->expired_at)
                            <div style="font-size: 0.72rem; color: #b45309; margin-top: 0.2rem; font-weight: 600;">
                                <i class="fa-solid fa-hourglass-half"></i> Sisa WA: {{ $b->expired_at->diffForHumans(['syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW]) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #16a34a;">Rp {{ number_format($b->total_price, 0, ',', '.') }}</div>
                        @if($b->discount && $b->discount > 0)
                            <div><span class="badge-discount-percent">{{ number_format($b->discount, 0) }}% OFF</span></div>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div class="action-btns">
                            <button type="button" class="btn-preview" onclick="openPreviewModal({{ json_encode($b) }}, {{ json_encode($b->room) }})">
                                <i class="fa-solid fa-eye"></i>
                                <span>Preview</span>
                            </button>

                            <button type="button" class="btn-edit" onclick="openEditModal({{ json_encode($b) }}, {{ json_encode($b->room) }})">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit</span>
                            </button>

                            <form action="{{ route('admin.bookings.destroy', $b->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="confirmDelete(this, 'Apakah Anda yakin ingin menghapus pemesanan {{ $b->booking_code }}?')">
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
@include('admin.bookings.modals.create')
@include('admin.bookings.modals.edit')
@include('admin.bookings.modals.preview')

@endsection

@section('scripts')
@include('admin.bookings.scripts')
@endsection
