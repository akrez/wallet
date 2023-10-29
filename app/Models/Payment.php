<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => PaymentStatusEnum::class,
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            $payment->unique_id = uniqid();
        });
    }

    public function getRouteKeyName()
    {
        return 'unique_id';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_key', 'key');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'payment_id', 'id');
    }
}
