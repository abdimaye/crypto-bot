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

    $trader->setInterval('5m')->setPeriods([12, 26])->simulate()->go('BTC/EUR', function($decision, $data) {
    	print_r($data);
    })->save($worker);
});

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
