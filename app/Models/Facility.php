<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'icon', 'description'])]
class Facility extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The rooms that belong to the facility.
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }
}
