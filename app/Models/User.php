<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function updateBalance()
    {
        $totalAmount = $this->transactions()
            ->select('currency_key', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('currency_key')
            ->pluck('total_amount', 'currency_key');

        $this->update([
            'balance' => json_encode($totalAmount->jsonSerialize())
        ]);

        return $totalAmount;
    }

    public function getBalance(String $currency): int
    {
        return $this->transactions()
            ->where('currency_key', $currency)
            ->sum('amount');
    }
}
