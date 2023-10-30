<?php

namespace App\Console\Commands;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Models\Currency;
use App\Models\Rate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchNavasanRatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-navasan-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currencyKeyToIsoCodes = Currency::whereNotIn('iso_code', ['irr'])->pluck('iso_code', 'key')->toArray();
        if (empty($currencyKeyToIsoCodes)) {
            throw new NotFoundException(__('currency.errors.currency_iso_code_is_empty'));
        }

        $navasanResponse = Http::get(config('settings.navasan.base_url'), [
            'api_key' => config('settings.navasan.api_key'),
        ]);
        if (!$navasanResponse->ok()) {
            throw new NotFoundException(__('currency.errors.currency_iso_code_is_empty'));
        }

        foreach ($currencyKeyToIsoCodes as $currencyKey => $currencyIsoCode) {
            $navasanItem = $navasanResponse->json($currencyIsoCode);
            if ($navasanItem) {
                Rate::create([
                    'currency_key' => $currencyKey,
                    'rate' => $navasanItem['value'],
                    'rate_currency_key' => 'rial',
                ]);
            }
        }
    }
}
