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

    	$trade = App\Trade::find(request('oid'))->first();

    	if ($trade) {

    		$pair = explode('/', $trade->symbol);

    		$base = $pair[0];

    		echo $result . '<br>';

    		$ticker = $trader->exchange->fetchTicker($trade->symbol);

    		if ($pair[1] == $trade->coin && $result > 0) {
    			// buy
    			echo "I have " . $trade->coin . ', I should buy ' . $base;

    			$newTrade = App\Trade::create([
    				'exchange' => $trader->exchange->id,
    				'symbol' => $trade->symbol,
    				'amount' => $trade->amount / $ticker['last'],
    				'coin' => $base,
    				'active' => 1
    			]);
    		} else if ($base == $trade->coin && $result < 0 ) {
    			// sell
    			echo "I have " . $base . ', I should sell for ' . $pair[1];
    			$newTrade = App\Trade::create([
    				'exchange' => $trader->exchange->id,
    				'symbol' => $trade->symbol,
    				'amount' => $trade->amount * $ticker['last'],
    				'coin' => $pair[1],
    				'active' => 1
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

    App\Trade::create([
    	'exchange' => request('exchange'),
    	'symbol' => request('symbol'),
    	'amount' => request('amount'),
    	'coin' => request('coin')
    ]);

    return 'created';
});