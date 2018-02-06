<?php

namespace App\Crypto;

/**
* Implementation of MACD - Moving Average Convergence Divergence
* Calculated by subtracting 26-day/period EMA from 12-day/period EMA		
*/

class Macd extends Trade
{
	protected $symbol;
	protected $candles = [];
	protected $currentSma;
	protected $currentEma;
	protected $timeframe = '5m';
	protected $periods = [12, 26];

	public function go($symbol, $lastTrade, $callback)
	{
		$this->symbol = $symbol;
		$timeframe = $this->timeframe;
		$periods = $this->periods;
		$shortPeriod = $periods[0];
		$longPeriod = $periods[1];

		// get market data
		// OHLCV - Open, High, Low, Close, Volume
		$this->candles = $this->exchange->fetchOHLCV($symbol, $timeframe, $since = null, $limit = $longPeriod);

		// TODO: only calculate SMA when previous EMA is not available

		// calculate SMA and EMA for short period
		$this->calculateSma($shortPeriod);

		$shortEma = $this->calculateEma($shortPeriod);
		
		// calculate SMA and EMA for long period
		$this->calculateSma($longPeriod);
		
		$longEma = $this->calculateEma($longPeriod);

		// compare EMAs and make buy/sell decision
		$result = $shortEma - $longEma; // buy if result > 0

		$data = ['short_ema' => $shortEma, 'long_ema' => $longEma];

		$decision = $this->decision($lastTrade, $result);

		return $callback($decision, $data);
	}

	public function setInterval(string $timeframe)
	{
		$this->timeframe = $timeframe;

		return $this;
	}

	public function setPeriods(array $periods)
	{
		$this->periods = $periods;

		return $this;
	}

	protected function decision($lastTrade, $result)
	{
		$pair = explode('/', $this->symbol);

		$base = $pair[0];

		if ($pair[1] == $lastTrade->coin && $result > 0) {
			// buy
			$decision = "I have " . $lastTrade->coin . ', I should buy ' . $base;;

			// $newTrade = App\Trade::create([
			// 	'worker_id' => $worker->id,
			// 	'amount' => $lastTrade->amount / $ticker['last'],
			// 	'coin' => $base,
			// ]);
		} else if ($base == $lastTrade->coin && $result < 0 ) {
			// sell
			// echo "I have " . $base . ', I should sell for ' . $pair[1];
			// $this->sell();
			$decision = "I have " . $base . ', I should sell for ' . $pair[1];

			// $newTrade = App\Trade::create([
			// 	'worker_id' => $worker->id,
			// 	'amount' => $lastTrade->amount * $ticker['last'],
			// 	'coin' => $pair[1],
			// ]);
		} else {
			// chill
			$decision = 'chill';
			// echo 'pair: ' . $pair[0] . '/' . $pair[1] . '<br>';
			// echo 'base: ' . $base . '<br>';
			// echo 'result: ' . $result . '<br>';

			// if ($result > 0) {
			// 	echo 'You should buy ' . $base . ' but you do not have any EUR';
			// } else if ($result < 0) {
			// 	echo 'You should sell ' . $base . ' but you do not have any ' . $base;
			// }
		}

		return $decision;
	}

	/**
	 * Calculate Exponential Moving Average
	 * EMA = {Close - EMA(previous period)} x multiplier + EMA(previous period)
	 * 
	 * @param  [type] $period [description]
	 * @return [type]         [description]
	 */
	protected function calculateEma($period)
	{
		$close = $this->candles[$period-1][4];

		$emaPrevious = $this->currentSma; // for now, TODO: check database for previous EMA

		$multiplier = 2 / ($period + 1); // TODO: set precision

		return $this->currentEma = ($close - $emaPrevious) * $multiplier + $emaPrevious;
	}

	/**
	 * Calculate Simple Moving Average
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	protected function calculateSma($period)
	{
		$total = 0;

		for ($i=0; $i < $period; $i++ ) {
			$total += $this->candles[$i][4]; // closing price
		}

		return $this->currentSma = $total / $period;
	}
}
