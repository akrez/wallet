<?php

namespace App\Listeners;

use App\Mail\PaymentApprovedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PaymentApprovedListener
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
        Mail::to($event->payment->user->email)->send(new PaymentApprovedMail($event->payment));
    }
}
