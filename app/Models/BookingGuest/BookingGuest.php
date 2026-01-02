<?php

namespace App\Models\BookingGuest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking\Booking;

class BookingGuest extends Model
{
    use HasFactory;

    protected $table = 'booking_guests';

    protected $fillable = [
        'booking_id',
        'full_name',
        'phone',
        'email',
        'is_primary',
    ];



    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

}