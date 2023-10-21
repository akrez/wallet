<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'currency'
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            $payment->unique_id = uniqid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
