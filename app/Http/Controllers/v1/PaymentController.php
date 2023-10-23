<?php

namespace App\Http\Controllers\v1;

use App\Enums\PaymentStatusEnum;
use App\Events\PaymentRejectedEvent;
use App\Events\PaymentStatusChangedEvent;
use App\Events\PaymentApprovedEvent;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::paginate(20);
        return Response::message('payment.messages.payment_list_found_successfully')
            ->data(PaymentResource::collection($payments))
            ->send();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $payment = new Payment($request->all());
        $payment->user_id = 1;
        $payment->status = PaymentStatusEnum::Pending;
        $payment->save();
        return Response::message('payment.messages.payment_successfuly_created')
            ->data(new PaymentResource($payment))
            ->status(200)
            ->send();
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return Response::message('payment.messages.payment_successfuly_found')
            ->data(new PaymentResource($payment))
            ->send();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function reject(Payment $payment)
    {
        if ($payment->status->value != PaymentStatusEnum::Pending->value) {
            throw new BadRequestException(__('payment.errors.you_can_only_decline_pending_payments'), 403);
        }

        $payment->status = PaymentStatusEnum::Rejected;
        $payment->status_updated_at = Carbon::now();
        $payment->status_updated_by = 1;
        $payment->save();

        PaymentRejectedEvent::dispatch($payment, PaymentStatusEnum::Rejected);

        return Response::message('payment.messages.the_payment_was_successfully_rejected')->data(new PaymentResource($payment))->send();
    }

    public function approve(Payment $payment)
    {
        if ($payment->status->value != PaymentStatusEnum::Pending->value) {
            throw new BadRequestException(__('payment.errors.you_can_only_decline_pending_payments'), 403);
        }

        $transactionExits = Transaction::wherePaymentId($payment->id)->first();
        if ($transactionExits) {
            throw new BadRequestException('payment.errors.this_payment_has_already_been_used', 403);
        }

        $payment->status = PaymentStatusEnum::Approved;
        $payment->status_updated_at = Carbon::now();
        $payment->status_updated_by = 1;
        $payment->save();

        PaymentApprovedEvent::dispatch($payment, PaymentStatusEnum::Approved);

        return Response::message('payment.messages.the_payment_was_successfully_approved')->data(new PaymentResource($payment))->send();
    }
}
