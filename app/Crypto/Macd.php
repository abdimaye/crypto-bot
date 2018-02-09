<?php

namespace App\Crypto;

use App\Worker;

/**
* Implementation of MACD - Moving Average Convergence Divergence
* Calculated by subtracting 26-day/period EMA from 12-day/period EMA		
*/

class Macd extends Trade
{
	protected $symbol;
	protected $pair;
	protected $candles = [];
	protected $currentSma;
	protected $currentEma;
	protected $timeframe = '5m';
	protected $periods = [12, 26];
	protected $decision = 'chill';

	public function go($symbol, $callback = '')
	{
		$this->symbol = $symbol;
		$this->pair = explode('/', $this->symbol);
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

		$data = ['symbol' => $this->symbol, 'pair' => $this->pair, 'short_ema' => $shortEma, 'long_ema' => $longEma];

		// $decision = $this->decision($lastTrade, $result);
		$this->decision = ($result < 0) ? 'sell' : 'buy';

		if (is_callable($callback)) {
			$callback($this->decision, $data);
		}

		return $this;
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

	public function save(Worker $worker)
	{
		$ticker = $this->exchange->fetchTicker($this->symbol);

		$pair = $this->pair;

		$lastTrade = $worker->trades()->orderBy('id', 'desc')->first();

		// The $base is always the asset which you are seeking to buy
		// when the price is low, and sell when it increases.
		// E.g. I have Euros and I want to buy Bitcoin.
		$base = $pair[0];

    	if ($base == $lastTrade->coin && $this->decision == 'sell') {
    		// sell a.k.a short position
    		$amount = $lastTrade->amount * $ticker['last'];
    		$coin = $pair[1]; // EUR

    		$worker->trades()->create([
    			'amount' => $amount,
    			'coin' => $coin
    		]);

    		print_r('created');
    	} else if ($pair[1] == $lastTrade->coin && $this->decision == 'buy') {
    		// buy - a.k.a long position
    		$amount = $lastTrade->amount / $ticker['last'];
    		$coin = $pair[0]; // BTC

    		$worker->trades()->create([
    			'amount' => $amount,
    			'coin' => $coin
    		]);

    		print_r('created');
    	}
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
