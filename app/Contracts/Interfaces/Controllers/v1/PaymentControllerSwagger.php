<?php

namespace App\Contracts\Interfaces\Controllers\V1;

use App\Http\Swaggers\Controller;
use App\Http\Requests\StorepaymentRequest;
use App\Http\Requests\UpdatepaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;

interface PaymentControllerSwagger extends Controller
{
    /**
     * @OA\Get(
     *     path="/V1/payments",
     *     operationId="index",
     *     tags={"Payment"},
     * 
     *     summary="index Payment",
     *     description="index Payment",
     *
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=201, description="Successful operation"),
     *      @OA\Response(response=202, description="Successful operation"),
     *      @OA\Response(response=204, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function index(Request $request);

    public function create();

    /**
     * @OA\Post(
     *     path="/V1/payments",
     *     operationId="store",
     *     tags={"Payment"},
     * 
     *     summary="store Payment",
     *     description="store Payment",
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\Schema(type="object", required={"title", "user_id", "amount", "currency", "payment_at"},
     *         @OA\Property(property="title", type="text"),
     *         @OA\Property(property="user_id", type="int"),
     *         @OA\Property(property="amount", type="text"),
     *         @OA\Property(property="currency", type="text"),
     *         @OA\Property(property="attach_file", type="text"),
     *         @OA\Property(property="payment_at", type="text"),
     *     ),),
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=201, description="Successful operation"),
     *     @OA\Response(response=202, description="Successful operation"),
     *     @OA\Response(response=204, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function store(StorePaymentRequest $request);

    /**
     * @OA\Get(
     *     path="/V1/payments/{id}",
     *     operationId="show",
     *     tags={"Payment"},
     * 
     *     summary="show Payment",
     *     description="show Payment",
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="payment id",
     *         required=true,
     *         example="1234567890qwer",
     *         @OA\Schema(type="string")
     *      ),
     *
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=201, description="Successful operation"),
     *      @OA\Response(response=202, description="Successful operation"),
     *      @OA\Response(response=204, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function show(Payment $payment);

    public function edit(Payment $payment);

    public function update(UpdatePaymentRequest $request, Payment $payment);

    /**
     * @OA\Delete(
     *      path="/api/V1/payments/{id}",
     *      operationId="DeletePayment",
     *      tags={"Payments"},
     *      summary="Delete Payment",
     *      description="Delete  payment",
     *      @OA\Response(response=201,description="Payment Successfuly Removed"),
     *      @OA\Response(response=403, description="Bad request"),
     *      @OA\Response(response=404, description="Not Found"),
     *      @OA\Parameter(
     *         description="Payment id",
     *         in="path",
     *         name="id",
     *         required=true,
     *     ),
     * )
     */
    public function destroy(Payment $payment);

    /**
     * @OA\Patch(
     *     path="/V1/payments/{id}/reject",
     *     operationId="reject",
     *     tags={"Payment"},
     * 
     *     summary="reject Payment",
     *     description="reject Payment",
     * 
     *     @OA\Parameter(
     *         description="payment id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *      ),
     * 
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=201, description="Successful operation"),
     *      @OA\Response(response=202, description="Successful operation"),
     *      @OA\Response(response=204, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function reject(Payment $payment);

    /**
     * @OA\Patch(
     *     path="/V1/payments/{id}/approve",
     *     operationId="approve",
     *     tags={"Payment"},
     * 
     *     summary="approve Payment",
     *     description="approve Payment",
     * 
     *     @OA\Parameter(
     *         description="payment id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *      ),
     * 
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=201, description="Successful operation"),
     *      @OA\Response(response=202, description="Successful operation"),
     *      @OA\Response(response=204, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function approve(Payment $payment);
}
