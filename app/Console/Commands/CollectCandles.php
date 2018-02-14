<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CollectCandles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:candles {--exchange=} {--interval=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $interval = $this->option('interval');

        $exchange = $this->option('exchange');
        $interval = $this->option('interval');

        $data = new \App\Crypto\Data($exchange);

        $data->CollectCandles($interval);
    }
}
