@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Ringkasan Dashboard')

@section('styles')
<style>
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .icon-indigo { background-color: #e0e7ff; color: #4f46e5; }
    .icon-emerald { background-color: #dcfce7; color: #16a34a; }
    .icon-amber { background-color: #fef3c7; color: #d97706; }
    .icon-rose { background-color: #ffe4e6; color: #e11d48; }

    .stat-details h3 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.25rem;
    }

    .stat-details .value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
    }

    /* Section Cards */
    .card {
        background-color: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .btn-action {
        background-color: #4f46e5;
        color: #ffffff;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.2s ease;
    }

    .btn-action:hover {
        background-color: #4338ca;
    }

    /* Data Table */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.9rem;
    }

    .data-table th {
        background-color: #f8fafc;
        padding: 0.875rem 1rem;
        font-weight: 600;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .badge-status {
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-confirmed { background-color: #dbeafe; color: #1e40af; }
    .status-pending { background-color: #fef3c7; color: #92400e; }
    .status-checked-in { background-color: #dcfce7; color: #166534; }
    .status-completed { background-color: #f1f5f9; color: #475569; }
</style>
@endsection

@section('content')

    <!-- KPI Stat Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-indigo">
                <i class="fa-solid fa-bed"></i>
            </div>
            <div class="stat-details">
                <h3>Total Kamar</h3>
                <div class="value">{{ $stats['total_rooms'] }} Unit</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-emerald">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="stat-details">
                <h3>Pemesanan Aktif</h3>
                <div class="value">{{ $stats['active_bookings'] }} Pemesanan</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-amber">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="stat-details">
                <h3>Pendapatan Bulan Ini</h3>
                <div class="value">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-rose">
                <i class="fa-solid fa-percent"></i>
            </div>
            <div class="stat-details">
                <h3>Tingkat Hunian</h3>
                <div class="value">{{ $stats['occupancy_rate'] }}%</div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Section -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Pemesanan Terbaru</h2>
            <a href="#" class="btn-action">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Pemesanan</span>
            </a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Booking</th>
                        <th>Nama Tamu</th>
                        <th>Kamar / Unit</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                        <tr>
                            <td style="font-weight: 700; color: #4f46e5;">{{ $booking['id'] }}</td>
                            <td style="font-weight: 600;">{{ $booking['guest_name'] }}</td>
                            <td>{{ $booking['room_type'] }}</td>
                            <td>{{ date('d M Y', strtotime($booking['check_in'])) }}</td>
                            <td>{{ date('d M Y', strtotime($booking['check_out'])) }}</td>
                            <td>
                                @php
                                    $statusClass = match($booking['status']) {
                                        'Confirmed' => 'status-confirmed',
                                        'Pending' => 'status-pending',
                                        'Checked In' => 'status-checked-in',
                                        default => 'status-completed',
                                    };
                                @endphp
                                <span class="badge-status {{ $statusClass }}">
                                    {{ $booking['status'] }}
                                </span>
                            </td>
                            <td style="font-weight: 600;">{{ $booking['total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
