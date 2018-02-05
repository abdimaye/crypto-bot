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

    // print_r($trader->go('BTC/EUR', $timeframe = '5m'));
    
    $trader->setInterval('5m')->setPeriods([12, 26])->go('BTC/EUR', function($result, $data) {
    	// print_r($result);
    	var_dump($data);
    	if ($result > 0) {
    		echo 'BUY if funds are available <br>';
    	} else if ($result < 0) {
    		echo 'SELL if holding <br>';
    	}
    	// print_r($data);
    });
});