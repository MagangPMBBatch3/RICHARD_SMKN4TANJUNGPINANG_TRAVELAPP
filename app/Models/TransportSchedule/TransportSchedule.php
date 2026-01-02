<?php

namespace App\Models\TransportSchedule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'transport_schedules';

    protected $fillable = [
        'transport_id',
        'origin_location_id',
        'destination_location_id',
        'departure_time',
        'arrival_time',
        'price',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time'   => 'datetime',
    ];

    public function transport()
    {
        return $this->belongsTo(
            \App\Models\Transport\Transport::class,
            'transport_id'
        );
    }

    public function originLocation()
    {
        return $this->belongsTo(
            \App\Models\TransportLocation\TransportLocation::class,
            'origin_location_id'
        );
    }

    public function destinationLocation()
    {
        return $this->belongsTo(
            \App\Models\TransportLocation\TransportLocation::class,
            'destination_location_id'
        );
    }
}
