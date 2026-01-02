<?php

namespace App\Models\HotelRoomPhoto;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\HotelRoom\HotelRoom;

class HotelRoomPhoto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hotel_room_id',
        'photo',
    ];

    public function room()
    {
        return $this->belongsTo(HotelRoom::class);
    }
}
