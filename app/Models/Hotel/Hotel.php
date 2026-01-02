<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\HotelRoom\HotelRoom;

class Hotel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'description',
        'photo',
    ];


    public function hotel_rooms()
    {
        return $this->hasMany(\App\Models\HotelRoom\HotelRoom::class, 'hotel_id');
    }

}
