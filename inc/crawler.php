
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
