@extends('admin.layouts.app')

@section('title', 'Detail Kamar & Kalender')
@section('page_title', 'Detail Kamar & Kalender Ketersediaan Booking')

@section('styles')
<style>
    /* Room Detail & Calendar Cards Grid */
    .room-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-top: 1.25rem;
    }

    .room-detail-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .room-calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        margin-top: 0.75rem;
        text-align: center;
    }

    .calendar-day-header {
        font-size: 0.65rem;
        font-weight: 700;
        color: #64748b;
        padding: 0.2rem 0;
        text-transform: uppercase;
    }

    .calendar-day-cell {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        position: relative;
    }

    .day-available {
        background-color: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .day-booked {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
        font-weight: 800;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .day-booked:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.2);
        z-index: 10;
    }

    .day-booked::before,
    .day-booked::after {
        content: '';
        position: absolute;
        width: 2px;
        height: 110%;
        background-color: #dc2626;
        top: -5%;
        left: 50%;
        opacity: 0.8;
        pointer-events: none;
    }

    .day-booked::before {
        transform: rotate(45deg);
    }

    .day-booked::after {
        transform: rotate(-45deg);
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

    .facility-badge-pill {
        background-color: #f1f5f9;
        color: #475569;
        padding: 0.15rem 0.45rem;
        border-radius: 6px;
        font-size: 0.75rem;
        display: inline-block;
        margin: 0.15rem 0.1rem;
    }
</style>
@endsection

@section('content')

<!-- Card Utama Detail Kamar & Kalender -->
<div class="card" style="width: 100%;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
        <div>
            <h2 class="card-title" style="margin-bottom: 0;">
                <i class="fa-solid fa-calendar-days" style="color: #4f46e5;"></i>
                Detail Kamar & Kalender Ketersediaan Booking
            </h2>
            <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.2rem;">
                Tanggal terbooking ditandai <span style="color: #dc2626; font-weight: 700;">(X) Merah</span>. <strong>Klik tanggal merah</strong> untuk langsung membuka detail pesanannya.
            </div>
        </div>

        <!-- Month Filter Form -->
        @php
            $calMonth = request('cal_month', date('m'));
            $calYear = request('cal_year', date('Y'));
            $firstDay = \Carbon\Carbon::createFromDate((int)$calYear, (int)$calMonth, 1);
            $daysInMonth = $firstDay->daysInMonth;
            $startOffset = $firstDay->dayOfWeek; // 0 = Sunday
        @endphp

        <form action="{{ route('admin.rooms.details') }}" method="GET" style="display: flex; align-items: center; gap: 0.5rem;">
            <select name="cal_month" class="form-select" style="width: auto; padding: 0.4rem 0.85rem; border-radius: 8px; font-weight: 600;" onchange="this.form.submit()">
                @for($m = 1; $m <= 12; $m++)
                    @php $mStr = sprintf('%02d', $m); @endphp
                    <option value="{{ $mStr }}" {{ $mStr == $calMonth ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }} {{ $calYear }}
                    </option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Grid Cards Kamar -->
    <div class="room-cards-grid">
        @foreach($rooms as $room)
            @php
                // Build map of booked dates 'YYYY-MM-DD' => booking info
                $bookedMap = [];
                foreach($room->bookings as $b) {
                    $checkIn = \Carbon\Carbon::parse($b->check_in_date);
                    $checkOut = \Carbon\Carbon::parse($b->check_out_date);

                    for ($dt = $checkIn->copy(); $dt->lt($checkOut); $dt->addDay()) {
                        $bookedMap[$dt->format('Y-m-d')] = [
                            'id' => $b->id,
                            'customer' => $b->customer_name,
                            'code' => $b->booking_code,
                        ];
                    }
                }

                $imgs = is_array($room->images) ? $room->images : [];
                $thumb = count($imgs) > 0 ? end($imgs) : null;
            @endphp

            <div class="room-detail-card">
                <div>
                    <!-- Header Card -->
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.85rem;">
                        <div>
                            <span class="badge-code">{{ $room->code }}</span>
                            <h3 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-top: 0.35rem;">{{ $room->name }}</h3>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 1rem; font-weight: 800; color: #16a34a;">Rp {{ number_format($room->final_price, 0, ',', '.') }}</div>
                            <div style="font-size: 0.7rem; color: #64748b;">/ malam</div>
                        </div>
                    </div>

                    <!-- Image & Facilities Overview -->
                    <div style="display: flex; gap: 0.85rem; margin-bottom: 1rem; align-items: center;">
                        <div style="width: 70px; height: 60px; border-radius: 10px; overflow: hidden; background-color: #f1f5f9; flex-shrink: 0;">
                            @if($thumb && file_exists(public_path($thumb)))
                                <img src="/{{ $thumb }}" alt="{{ $room->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                                    <i class="fa-solid fa-bed" style="font-size: 1.25rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 0.2rem;">Fasilitas Utama</div>
                            <div>
                                @forelse($room->facilities->take(3) as $f)
                                    <span class="facility-badge-pill" style="font-size: 0.7rem; padding: 0.1rem 0.35rem;">
                                        <i class="fa-solid {{ $f->icon ?? 'fa-check' }}" style="font-size: 0.65rem; color: #4f46e5;"></i> {{ $f->name }}
                                    </span>
                                @empty
                                    <span style="font-size: 0.75rem; color: #94a3b8; font-style: italic;">Tanpa fasilitas</span>
                                @endforelse
                                @if($room->facilities->count() > 3)
                                    <span style="font-size: 0.7rem; color: #4f46e5; font-weight: 600;">+{{ $room->facilities->count() - 3 }} lainnya</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar Section -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 0.85rem; margin-top: 0.5rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #334155;">Kalender {{ $firstDay->translatedFormat('F Y') }}</span>
                        <div style="display: flex; gap: 0.5rem; font-size: 0.68rem; font-weight: 600;">
                            <span style="color: #16a34a;">🟢 Bebas</span>
                            <span style="color: #dc2626;">🔴 X Terbooking (Klik)</span>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="room-calendar-grid">
                        <!-- Day Headers -->
                        <div class="calendar-day-header">Min</div>
                        <div class="calendar-day-header">Sen</div>
                        <div class="calendar-day-header">Sel</div>
                        <div class="calendar-day-header">Rab</div>
                        <div class="calendar-day-header">Kam</div>
                        <div class="calendar-day-header">Jum</div>
                        <div class="calendar-day-header">Sab</div>

                        <!-- Empty offset days -->
                        @for($i = 0; $i < $startOffset; $i++)
                            <div class="calendar-day-cell" style="background: transparent;"></div>
                        @endfor

                        <!-- Days of Month -->
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $dStr = sprintf('%04d-%02d-%02d', $calYear, $calMonth, $d);
                                $isBooked = isset($bookedMap[$dStr]);
                                $bookInfo = $isBooked ? $bookedMap[$dStr] : null;
                            @endphp

                            @if($isBooked)
                                <a href="{{ route('admin.bookings.index') }}?code={{ $bookInfo['code'] }}" class="calendar-day-cell day-booked" style="text-decoration: none;" title="Terbooking oleh: {{ $bookInfo['customer'] }} ({{ $bookInfo['code'] }}) - Klik untuk lihat detail pemesanan">
                                    {{ $d }}
                                </a>
                            @else
                                <div class="calendar-day-cell day-available" title="Kamar Bebas">
                                    {{ $d }}
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
