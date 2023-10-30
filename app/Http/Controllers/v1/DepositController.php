<?php

namespace App\Http\Controllers\v1;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepositTransferRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DepositController extends Controller
{
    function transfer(DepositTransferRequest $request)
    {
        DB::beginTransaction();

        try {

            $fromUser = User::findOrFail($request->from_user);
            $toUser = User::findOrFail($request->to_user);

            $fromUser->transactions()->lockForUpdate();
            $toUser->transactions()->lockForUpdate();

            $fromUserBalance = $fromUser->getBalance($request->currency_key);
            $toUserBalance = $toUser->getBalance($request->currency_key);

            if ($fromUserBalance < $request->amount) {
                DB::rollBack();
                throw new BadRequestException(__('deposit.errors.from_user_balance_lower_than_transfer_amount'), 400);
            }

            Transaction::create([
                'user_id' => $fromUser->id,
                'amount' => $request->amount * -1,
                'currency_key' => $request->currency_key,
                'balance' => $fromUserBalance - $request->amount,
            ]);

            Transaction::create([
                'user_id' => $toUser->id,
                'amount' => $request->amount,
                'currency_key' => $request->currency_key,
                'balance' => ($toUserBalance + $request->amount),
            ]);

            $fromUser->updateBalance();
            $toUser->updateBalance();

            DB::commit();
            return Response::message(__('deposit.messages.balance_transferred'))->send();
        } catch (\Exception $e) {

            DB::rollback();
            throw new BadRequestException(__('deposit.errors.unknown_error'), 400);
        }
    }
}
