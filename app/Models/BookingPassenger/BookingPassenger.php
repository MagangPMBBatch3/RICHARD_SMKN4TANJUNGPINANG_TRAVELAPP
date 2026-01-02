<?php

namespace App\Models\BookingPassenger;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Booking\Booking;
use App\Models\User;

class BookingPassenger extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_id',
        'user_id',
        'full_name',
        'birth_date',
        'identity_number',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
