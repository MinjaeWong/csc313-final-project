
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - CRAWLER
/////////////////////////////////////////////////////////////////////////////////////////////////////

// Config & Class Library
include('config.php');

// Save all coins first
if (defined('GET_CURRENCY_PAIRS') && is_array(GET_CURRENCY_PAIRS) && count(GET_CURRENCY_PAIRS) > 0) {
    foreach (GET_CURRENCY_PAIRS as $pair) {
        $bs = new BitStamp($pair);
        $data = $bs->get_data();

        if ($data['last'] > 0) {
            $d = new DateTime();
            $datestamp = $d->format('Y-m-d\TH:i:s');

            $prices = new PriceList($datestamp, $data['last'], $data['bid'], $data['ask'], $data['volume'], $pair);
            $prices->save(true);
            echo "Pulled data ticker for $pair.<br />";
        } else {
            echo "Error returning Prices for $pair. API Error or Server is down. Next Attempt in 10 Minutes.<br />";
        }
    }
}

// Get latest Data
$bs   = new BitStamp();
$data = $bs->get_data();

if($data['last'] > 0){
    $d = new DateTime();
    $datestamp = $d->format('Y-m-d\TH:i:s');

    // Save Data to Db
    $prices = new PriceList($datestamp, $data['last'], $data['bid'], $data['ask'], $data['volume']);
    $prices->save();

    // Get Price Array
    $prices = new PriceList();
    $priceList = $prices->get_all(4608, 'btcusd'); // The more data, the more accurate the averages. Too much could chug. 1 Month is plenty.

    // Set Coin Config
    $coinConfig = coinConfig('btcusd'); // Hard Coding BTC until Multi-Coins ready to loop through.

    // Build Complete Data Set
    if($priceList){
        $ma = new MovingAverages();
        $completeData = $ma->build_averages(array_reverse($priceList), $coinConfig['SMAMultiplier']);

        $gloria = new Gloria();
        $gloria->run($completeData, $coinConfig, 'live');
        echo "Success: Crawlers gon crawl.";

    }else{
        echo "Error fetching price list";
    }
}else{
    echo "Error returning prices. API error or server is down. Next attempt in 10 minutes.";
}