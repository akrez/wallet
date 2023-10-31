<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

/**
 * Class Payment
 * 
 * @property int $id
 * @property string|null $unique_id
 * @property int $user_id
 * @property float $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $status
 * @property Carbon|null $status_updated_at
 * @property int|null $status_updated_by
 * @property string $currency_key
 * @property string|null $deleted_at
 * 
 * @property Currency $currency
 * @property User|null $user
 * @property Transaction|null $transaction
 *
 * @package App\Models
 */
class Payment extends Model
{
	use HasFactory;
	use SoftDeletes;
	use LogsActivity;

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

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()->logUnguarded();
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
