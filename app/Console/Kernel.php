<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        
        $schedule->call(function() {
            $trader = new \App\Crypto\Macd('gdax');
    
            $worker = \App\Worker::find(request('oid'));

            $lastTrade = $worker->trades()->orderBy('id', 'desc')->first();

            $trader->setInterval('1m')->setPeriods([12, 26])->simulate()->go('BTC/EUR', $lastTrade, function($decision, $data) use ($trader, $worker, $lastTrade) {

                $ticker = $trader->exchange->fetchTicker($data['symbol']);

                if ($decision == 'sell') {
                    // sell BTC
                    $amount = $lastTrade->amount * $ticker['last'];
                    $coin = $data['pair'][1]; // BTC

                    $worker->trades()->create([
                        'amount' => $amount,
                        'coin' => $coin
                    ]);

                    print_r('created');
                } else if ($decision == 'buy') {
                    // buy BTC
                    $amount = $lastTrade->amount / $ticker['last'];
                    $coin = $data['pair'][0]; // EUR

                    $worker->trades()->create([
                        'amount' => $amount,
                        'coin' => $coin
                    ]);

                    print_r('created');
                }

                print_r($decision);

                // print_r($ticker);
            });
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
