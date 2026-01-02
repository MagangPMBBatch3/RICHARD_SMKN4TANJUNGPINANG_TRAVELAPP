<?php
namespace App\Models\PaymentVerification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Payment\Payment;
use App\Models\User;

class PaymentVerification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_id',
        'admin_id',
        'status',
        'note',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
