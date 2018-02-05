<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $trader = new \App\Crypto\Macd('gdax');
    
    $trader->setInterval('5m')->setPeriods([12, 26])->go('BTC/EUR', function($result, $data) use ($trader) {

    	$worker = App\Worker::find(request('oid'));

    	if ($worker) {

    		$trade = $worker->trades()->orderBy('id', 'desc')->first();

    		$worker = $worker->first();

    		$pair = explode('/', $worker->symbol);

    		$base = $pair[0];

    		echo $result . '<br>';

    		$ticker = $trader->exchange->fetchTicker($worker->symbol);

    		// print_r($trade);

    		// return ;
    		if ($pair[1] == $trade->coin && $result > 0) {
    			// buy
    			echo "I have " . $trade->coin . ', I should buy ' . $base;

    			$newTrade = App\Trade::create([
    				'worker_id' => $worker->id,
    				'amount' => $trade->amount / $ticker['last'],
    				'coin' => $base,
    			]);
    		} else if ($base == $trade->coin && $result < 0 ) {
    			// sell
    			echo "I have " . $base . ', I should sell for ' . $pair[1];
    			$newTrade = App\Trade::create([
    				'worker_id' => $worker->id,
    				'amount' => $trade->amount * $ticker['last'],
    				'coin' => $pair[1],
    			]);
    		} else {
    			// chill
    			echo 'pair: ' . $pair[0] . '/' . $pair[1] . '<br>';
    			echo 'base: ' . $base . '<br>';
    			echo 'result: ' . $result . '<br>';

    			if ($result > 0) {
    				echo 'You should buy ' . $base . ' but you do not have any EUR';
    			} else if ($result < 0) {
    				echo 'You should sell ' . $base . ' but you do not have any ' . $base;
    			}
    		}
    	
    		// print_r($trader);
    		return;
    	}
    });
});

Route::get('/create-job', function () {

    $worker = App\Worker::create([
    	'exchange' => request('exchange'),
    	'symbol' => request('symbol'),
    	'active' => 1
    ]);

    // print_r($worker);

    App\Trade::create([
    	'worker_id' => $worker->id,
    	'amount' => request('amount'),
    	'coin' => request('coin'),
    ]);

    return 'created';
});