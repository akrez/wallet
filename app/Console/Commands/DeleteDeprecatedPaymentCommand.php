<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatusEnum;
use App\Jobs\DeleteDeprecatedPaymentJob;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteDeprecatedPaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:delete-deprecate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete deprecated payment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Payment::where('status', PaymentStatusEnum::Pending)
            ->where('created_at', '<', Carbon::now()->subHours(config('settings.payment.delete_deprecated_payment_after_hours')))
            ->chunk(config('settings.payment.delete_deprecated_payment_count'), function ($payments) {
                DeleteDeprecatedPaymentJob::dispatch($payments->pluck('id')->toArray());
            });
    }
}
