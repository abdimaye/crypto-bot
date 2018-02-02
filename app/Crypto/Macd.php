<?php

namespace App\Crypto;

/**
* Implementation of MACD - Moving Average Convergence Divergence
* Calculated by subtracting 26-day/period EMA from 12-day/period EMA		
*/

class Macd extends Trade
{
	protected $candles = [];
	protected $currentSma;
	protected $currentEma;

	public function go($symbol, $timeframe = '5m')
	{
		// get market data
		// OHLCV - Open, High, Low, Close, Volume
		$this->candles = $this->exchange->fetchOHLCV($symbol, $timeframe, $since = null, $limit = 26);

		// TODO: only calculate SMA when previous EMA is not available

		// calculate SMA and EMA for short period
		print_r('SMA12: ' . $this->calculateSma(12) . '<br>');

		$shortEma = $this->calculateEma(12);
		
		// calculate SMA and EMA for long period
		print_r('SMA26: ' . $this->calculateSma(26)  . '<br>');

		// calculate EMA for both
		
		$longEma = $this->calculateEma(26);
		print_r('EMA12: ' . $shortEma . '<br>');
		print_r('EMA26: ' . $longEma . '<br>');

		// compare EMAs and make buy/sell decision
		if ($shortEma > $longEma) {
			return 'BUY if funds are available';
		} else if ($longEma > $shortEma) {
			return 'SELL if holding';
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
