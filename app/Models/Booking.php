<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'room_id',
    'booking_code',
    'customer_name',
    'customer_address',
    'customer_phone',
    'customer_sosmed',
    'check_in_date',
    'check_out_date',
    'total_nights',
    'room_price',
    'discount',
    'total_price',
    'status',
    'expired_at',
    'extra_facilities'
])]
class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'expired_at' => 'datetime',
            'status' => 'integer',
        ];
    }

    /**
     * Get the room that owns the booking.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get Status Label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            2 => 'Lunas',
            1 => 'Menunggu Pembayaran (Pending)',
            0 => 'Dibatalkan / Expired',
            default => 'Pending',
        };
    }

    /**
     * Get Status Badge HTML Class & Style
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            2 => ['label' => 'Lunas', 'bg' => '#dcfce7', 'color' => '#166534', 'icon' => 'fa-circle-check'],
            1 => ['label' => 'Pending (WA)', 'bg' => '#fef3c7', 'color' => '#b45309', 'icon' => 'fa-clock'],
            0 => ['label' => 'Dibatalkan', 'bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => 'fa-circle-xmark'],
            default => ['label' => 'Pending', 'bg' => '#f1f5f9', 'color' => '#475569', 'icon' => 'fa-question-circle'],
        };
    }
}
