<?php

namespace App\Http\Controllers\v1;

use App\Enums\PaymentStatusEnum;
use App\Events\PaymentRejectedEvent;
use App\Http\Swaggers\v1\PaymentControllerSwagger;
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
use Illuminate\Http\Request;

class PaymentController extends Controller implements PaymentControllerSwagger
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payments = Payment::paginate(config('settings.pagination.default_per_page'));
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
        $existsPayment = Payment::query()
            ->where('amount', $request->amount)
            ->where('currency_key', $request->currency_key)
            ->where('created_at', '>', Carbon::now()->subMinutes(5))
            ->first();
        if ($existsPayment) throw new BadRequestException(__('payment.messages.duplicate_payment_exists', [
            'amount' => $existsPayment->amount,
            'currency' => $existsPayment->currency->name,
        ]));

        $payment = Payment::create([
            'user_id' => 1,
            'amount' => $request->amount,
            'currency_key' => $request->currency_key,
            'type' => $request->type,
            'unique_id' => uniqid(),
            'status' => PaymentStatusEnum::Pending,
        ]);

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

    public function destroy(Payment $payment)
    {
        if ($payment->status != PaymentStatusEnum::Pending) {
            return throw new BadRequestException(__('payment.errors.you_can_delete_pending_payments'));
        }

        $payment->delete();

        return Response::message(__('payment.messages.payment_successfully_removed'))->send();
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
