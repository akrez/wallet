<?php

namespace App\Listeners;

use App\Mail\PaymentApprovedMail;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PaymentTransactionListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $balance = Transaction::where('user_id', $event->payment->user_id)
            ->where('currency_key', $event->payment->currency_key)
            ->sum('amount');
        $balance += $event->payment->amount;

        $transaction = new Transaction();
        $transaction->amount = $event->payment->amount;
        $transaction->payment_id = $event->payment->id;
        $transaction->balance = $balance;
        $transaction->currency_key = $event->payment->currency->key;
        $transaction->user_id = $event->payment->user_id;
        $transaction->save();
    }
}
