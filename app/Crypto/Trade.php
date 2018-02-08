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
	 */
	public function simulate()
	{
		$this->simulate = true;

		return $this;
	}
	
	/**
	 * Act on a decision made to buy or sell.
	 * 
	 * @param  string $position buy/sell
	 * @return [type]           [description]
	 */
	
	public function act($position) 
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