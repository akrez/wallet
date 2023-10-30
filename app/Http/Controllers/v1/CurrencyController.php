<?php

namespace App\Http\Controllers\v1;

use App\Events\Currency\CurrencyActivated;
use App\Events\Currency\CurrencyDeActivated;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyStoreRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::paginate(config('settings.pagination.default_per_page'));
        return Response::message('payment.messages.payment_list_found_successfully')
            ->data(CurrencyResource::collection($currencies))
            ->send();
    }

    public function store(CurrencyStoreRequest $request)
    {
        $currency = Currency::create($request->all());
        return Response::message('currency.messages.currency_successfully_created')
            ->data(new CurrencyResource($currency))
            ->status(200)
            ->send();
    }

    public function active(Currency $currency)
    {
        if ($currency->is_active) {
            throw new BadRequestException(__('currency.errors.currency_is_currently_active_and_cannot_be_reactivated'));
        }
        $currency->update(['is_active' => 1]);
        return Response::message('currency.messages.the_currency_has_been_activated_successfully')
            ->data(new CurrencyResource($currency))
            ->send();
    }

    public function deactive(Currency $currency)
    {
        if (!$currency->is_active) {
            throw new BadRequestException(__('currency.errors.currency_is_currently_inactive_and_cannot_be_reactivated'));
        }
        $currency->update(['is_active' => 0]);
        return Response::message('currency.messages.the_currency_has_been_deactivated_successfully')
            ->data(new CurrencyResource($currency))
            ->send();
    }
}
