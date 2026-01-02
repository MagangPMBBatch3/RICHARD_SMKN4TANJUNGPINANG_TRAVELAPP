<?php

namespace App\Models\RoomType;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\HotelRoom\HotelRoom;

class RoomType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function rooms()
    {
        return $this->hasMany(HotelRoom::class);
    }
}
