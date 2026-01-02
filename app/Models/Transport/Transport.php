<?php

namespace App\Models\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TransportSchedule\TransportSchedule;

class Transport extends Model
{
    use SoftDeletes;

    protected $table = 'transports';

    protected $fillable = [
        'type',
        'name',
        'code',
        'capacity',
        'price_per_seat',
        'photo',
    ];

    public function schedules()
    {
        return $this->hasMany(
            TransportSchedule::class,
            'transport_id',
            'id'
        );
    }
}
