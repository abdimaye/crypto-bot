<?php

namespace App\Crypto;

abstract class Trade
{
	public $exchange;

	public function __construct($exchange = 'gdax') 
	{
		$exchange = '\ccxt\\' . $exchange;
		$this->exchange = new $exchange;
	}
	
	public function buy() 
	{

	}

	public function sell()
	{
		
	}

	public function checkFunds()
	{
		
	}

	public function setSpendingLimit($limit)
	{
		
	}
}