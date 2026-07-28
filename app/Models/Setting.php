<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'logo',
    'homestay_name',
    'wa_number',
    'address',
    'gmap_link',
    'media_assets',
])]
class Setting extends Model
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
            'media_assets' => 'array',
        ];
    }

    /**
     * Helper to get singleton setting instance.
     */
    public static function getSetting()
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'logo' => null,
                'homestay_name' => 'Faradisa HomeStay',
                'wa_number' => '081234567890',
                'address' => 'Jl. Utama Homestay, Akses Mudah & Aman',
                'gmap_link' => 'https://maps.google.com/?q=Faradisa+HomeStay',
                'media_assets' => [],
            ]
        );
    }
}
