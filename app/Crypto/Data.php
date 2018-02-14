<?php

namespace App\Crypto;

use DB;
use ccxt\DDoSProtection;
// use MongoDB\BSON\UTCDateTime;
use MongoDB\Driver\Exception\BulkWriteException;

/**
* Collect and store data from different exchanges.
*/

class Data
{
	protected $exchange;
	protected $exchangeName;
	protected $markets;

	function __construct($exchange)
	{
		$this->exchangeName = $exchange;

		$exchange = '\ccxt\\' . $exchange;

		$this->exchange = new $exchange;
	}

	public function collectCandles(string $interval = '1m')
	{
		$exchange = $this->exchangeName;

		$tradingPairs = config('markets')[$exchange];

		foreach($tradingPairs as $symbol) {
			
			try {
				$candles = $this->exchange->fetchOHLCV($symbol, $interval);
			} catch (DDoSProtection $e) {
				sleep(1);
				continue;
			}
			
			$data = [];

			foreach($candles as $index => $candle) {
				
				// $date = new \MongoDB\BSON\UTCDateTime($candle[0]);//

				$date = gmdate('Y-m-d H:i:s.000',$candle[0] / 1000);

				print_r($date);

				$open = ['open' => $candles[$index][1]];
				$high = ['high' => $candles[$index][2]];
				$low = ['low' => $candles[$index][3]];
				$close = ['close' => $candles[$index][4]];
				$volume = ['volume' => $candles[$index][5]];

				$data[] = ['_id' => $date, $open, $high, $low, $close, $volume];
			}

			try {
				$test = DB::connection('mongodb')->collection("candles_{$exchange}_{$interval}_{$symbol}")->insert($data);
				// $test = \App\Test::insert($data);
			} catch (BulkWriteException $e) {
				continue;
			}
		
		}

		// return $candles;
	}
}