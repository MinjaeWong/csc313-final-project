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
$graphData = $price