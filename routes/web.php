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


Route::get('/workers', 'WorkerController@index');

Route::get('/workers/{id}', 'WorkerController@show');

Route::post('/workers/store', 'WorkerController@store');

Route::get('/test', function () {
    $trader = new \App\Crypto\Macd('gdax');
    
    $worker = App\Worker::find(request('oid'));

	$lastTrade = $worker->trades()->orderBy('id', 'desc')->first();

    $trader->setInterval('5m')->setPeriods([12, 26])->simulate()->go('BTC/EUR', $lastTrade, function($decision, $data) use ($trader, $worker, $lastTrade) {

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

Route::get('/create-job', function () {

    // $worker = App\Worker::create([
    // 	'exchange' => request('exchange'),
    // 	'symbol' => request('symbol'),
    // 	'active' => 1
    // ]);

    // // print_r($worker);

    // $worker->trades()->create([
    // 	// 'worker_id' => $worker->id,
    // 	'amount' => request('amount'),
    // 	'coin' => request('coin'),
    // ]);

    return 'created';
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
