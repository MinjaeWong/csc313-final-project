
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - SIMULATION TEST BED to debug and refine the Algorithm
/////////////////////////////////////////////////////////////////////////////////////////////////////

// Config & Class Library
include('config.php');

error_reporting(E_ALL ^ E_WARNING);

$db = new Db();

$currency = $_GET['currencypair'];
$coinConfig = coinConfig($currency);

// Get Price Array
$prices = new PriceList();
$priceList = $prices->get_all(null, $currency); // Set limit number for last period or 'null' for all history
$graphData = $prices->get_graphData(36, $currency); // 6 Hours

// Build Complete Data Set
if($priceList){
    $ma = new MovingAverages();
    $completeData = $ma->build_averages(array_reverse($priceList), $coinConfig['SMAMultiplier']);

    // Run Simulation
    $gloria = new Gloria();
    $gloria->run($completeData, $coinConfig, 'simulation');
    $gloria->get_profitReport();

}else{
    echo "Error fetching price list.";
}