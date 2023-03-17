<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\House;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the currency exchange rates and prices';

    /**
     * Execute the console command.
     */

    public function handle()
    {

        $exchangeRate = getExchangeRate();

        $houses = House::all();
        foreach ($houses as $house) {
            switch ($house->default_currency) {
                case 'USD':
                    $house->price_rub = $house->price_usd / $exchangeRate;
                    break;
                case 'RUB':
                    $house->price_usd = $house->price_rub * $exchangeRate;
                    break;
                default:
                    // handle unexpected currency type here
                    break;
            }
            $house->save();
        }


        $this->info('House prices updated successfully.');
    }

}
