<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - Simple Dashboard to display recent automated trades, prices etc.
/////////////////////////////////////////////////////////////////////////////////////////////////////

// Config & Class Library
require_once('../env.php');
require_once('classes/Db.php');
require_once('classes/PriceList.php');
require_once('classes/BitStamp.php');
require_once('classes/Trade.php');

$db = new Db();

// Get Price Data
$prices = new PriceList();
$graphData = $prices->get_graphData(72, 'btcusd');
$lastPrice = $prices->get_lastChange('btcusd');

// Get Account Data
$bs = new BitStamp();
$accountValue = $bs->get_balance();
$accountValueUSD = number_format(($accountValue['btc_balance'] * $lastPrice['last_price']) + $accountValue['usd_balance']