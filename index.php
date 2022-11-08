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
$accountValueUSD = number_format(($accountValue['btc_balance'] * $lastPrice['last_price']) + $accountValue['usd_balance'], 2, '.', '');

// Get Trade Data
$profit = new Trade();
$profitReport = $profit->get_profitReport();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iS