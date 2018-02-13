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
   
            $worker = \App\Worker::isActive(2);

            $trader->setInterval('5m')->setPeriods([12, 26])->simulate()->go('BTC/EUR', function($decision, $data) {
                print_r($data);
            })->save($worker);
        })->everyMinute();

        $schedule->call(function() {
            $trader = new \App\Crypto\Macd('gdax');
   
            $worker = \App\Worker::isActive(4);

            $trader->setInterval('1m')->setPeriods([12, 26])->simulate()->go('BTC/EUR', function($decision, $data) {
                print_r($data);
            })->save($worker);
        })->cron('*/2 * * * *'); // every two minutes

        $schedule->call(function() {
            $trader = new \App\Crypto\Macd('gdax');
   
            $worker = \App\Worker::isActive(5);

            $trader->setInterval('15m')->simulate()->go('BTC/EUR', function($decision, $data) {
                print_r($data);
            })->save($worker);
        })->everyMinute(); 

        $schedule->call(function() {
            $trader = new \App\Crypto\Macd('gdax');
   
            $worker = \App\Worker::isActive(6);

            $trader->setInterval('1h')->simulate()->go('BTC/EUR', function($decision, $data) {
                print_r($data);
            })->save($worker);
        })->everyMinute(); 

        // data aggregator
        
        // every minute
        $schedule->call(function() {
            $data = (new \App\Crypto\Data('gdax'))->collectCandles('1m');
            $data = (new \App\Crypto\Data('binance'))->collectCandles('1m');
        })->everyMinute();

        // every five mimutes
        $schedule->call(function() {
            $data = (new \App\Crypto\Data('gdax'))->collectCandles('5m');
            $data = (new \App\Crypto\Data('binance'))->collectCandles('5m');
        })->everyFiveMinutes();

        // every 15 minutes
        $schedule->call(function() {
            $data = (new \App\Crypto\Data('gdax'))->collectCandles('15m');
            $data = (new \App\Crypto\Data('binance'))->collectCandles('15m');
        })->everyFifteenMinutes();

        // every hour
        $schedule->call(function() {
            $data = (new \App\Crypto\Data('gdax'))->collectCandles('1h');
            $data = (new \App\Crypto\Data('binance'))->collectCandles('1h');
        })->hourly();

        // every day
        $schedule->call(function() {
            $data = (new \App\Crypto\Data('gdax'))->collectCandles('1d');
            $data = (new \App\Crypto\Data('binance'))->collectCandles('1d');
        })->daily();
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
