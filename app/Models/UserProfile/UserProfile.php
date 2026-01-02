<?php

namespace App\Models\UserProfile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class UserProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'phone_number',
        'email',
        'address',
        'identity_type',
        'identity_number',
        'identity_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
