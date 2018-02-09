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
