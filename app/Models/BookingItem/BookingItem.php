<?php

namespace App\Models\BookingItem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Booking\Booking;
use App\Models\HotelRoom\HotelRoom;
use App\Models\TransportSchedule\TransportSchedule;

class BookingItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_id',
        'item_type',
        'reference_id',
        'quantity',
        'price',
        'subtotal',
        'check_in',
        'check_out'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function hotelRoom()
    {
        return $this->belongsTo(
            HotelRoom::class,
            'reference_id'
        );
    }

    public function transportSchedule()
    {
        return $this->belongsTo(
            TransportSchedule::class,
            'reference_id'
        );
    }


    public function getItemAttribute()
    {
        if ($this->item_type === 'hotel_room') {
            return $this->hotelRoom;
        }

        if ($this->item_type === 'transport_schedule') {
            return $this->transportSchedule;
        }

        return null;
    }

    public function getDetailAttribute()
    {
        return $this->item;
    }
}
