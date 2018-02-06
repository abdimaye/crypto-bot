<?php

namespace App\Crypto;

abstract class Trade
{
	public $exchange;
	public $simulate = false;

	public function __construct($exchange = 'gdax') 
	{
		$exchange = '\ccxt\\' . $exchange;
		$this->exchange = new $exchange;
	}

	/**
	 * Paper trading.
	 * 
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function simulate()
	{
		$this->simulate = true;

		// return $this->exchange->fetchTicker($worker->symbol);
		return $this;
	}
	
	protected function buy() 
	{
		if ($this->simulate) {
			// do nothing
			return ;
		}
	}

	protected function sell()
	{
		if ($this->simulate) {
			// do nothing
			return ;
		}
	}

	public function checkFunds()
	{
		
	}

	public function setSpendingLimit($limit)
	{
		
	}
}