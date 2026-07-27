<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'name', 'price', 'discount', 'images'])]
class Room extends Model
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
            'images' => 'array',
        ];
    }

    /**
     * The facilities that belong to the room.
     */
    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    /**
     * Calculate price after discount percentage.
     */
    public function getFinalPriceAttribute()
    {
        if ($this->discount && $this->discount > 0) {
            return $this->price - ($this->price * ($this->discount / 100));
        }

        return $this->price;
    }
}
