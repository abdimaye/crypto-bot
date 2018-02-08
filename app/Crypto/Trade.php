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