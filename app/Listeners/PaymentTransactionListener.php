<?php

namespace App\Listeners;

use App\Mail\PaymentApprovedMail;
use App\Models\Transaction;
use App\Models\User;
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
        $user = User::findOrFail($event->payment->user_id);

        $balance = Transaction::where('user_id', $user->id)
            ->where('currency_key', $event->payment->currency_key)
            ->sum('amount');
        $balance += $event->payment->amount;

        Transaction::create([
            'amount' => $event->payment->amount,
            'payment_id' => $event->payment->id,
            'balance' => $balance,
            'currency_key' => $event->payment->currency->key,
            'user_id' => $user->id,
        ]);

        $user->updateBalance();
    }
}
