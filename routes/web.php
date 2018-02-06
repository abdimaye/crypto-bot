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


Route::get('/', 'WorkerController@index');

Route::get('/worker/{id}/trades', 'TradeController@index');

Route::get('/test', function () {
    $trader = new \App\Crypto\Macd('gdax');
    
    $worker = App\Worker::find(request('oid'));

	$lastTrade = $worker->trades()->orderBy('id', 'desc')->first();

	$worker = $worker->first();

    $trader->setInterval('5m')->setPeriods([12, 26])->simulate()->go('BTC/EUR', $lastTrade, function($decision, $data) use ($trader) {
    	// $ticker = $this->exchange->fetchTicker($this->symbol);
    	print_r($decision);
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
