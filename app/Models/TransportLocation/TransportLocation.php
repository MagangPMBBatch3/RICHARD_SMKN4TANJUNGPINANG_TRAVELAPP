<?php

namespace App\Models\TransportLocation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TransportSchedule\TransportSchedule;

class TransportLocation extends Model
{
    use SoftDeletes;

    protected $table = 'transport_locations';

    protected $fillable = [
        'type',  
        'name',  
        'code',   
        'city',   
        'photo',
    ];


    public function originSchedules()
    {
        return $this->hasMany(
            TransportSchedule::class,
            'origin_location_id',
            'id'
        );
    }

    public function destinationSchedules()
    {
        return $this->hasMany(
            TransportSchedule::class,
            'destination_location_id',
            'id'
        );
    }
}
