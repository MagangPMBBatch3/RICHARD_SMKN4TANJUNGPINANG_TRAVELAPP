<?php

namespace App\Models\HotelRoom;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Hotel\Hotel;
use App\Models\RoomType\RoomType;
use App\Models\HotelRoomPhoto\HotelRoomPhoto;

class HotelRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'name',
        'description',
        'price',
        'quantity',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room_type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function photos()
    {
        return $this->hasMany(HotelRoomPhoto::class);
    }
}
