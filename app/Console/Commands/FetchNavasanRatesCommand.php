<?php

namespace App\Console\Commands;

use App\Jobs\FetchNavasanRatesJob;
use Illuminate\Console\Command;

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
        FetchNavasanRatesJob::dispatch();
    }
}
