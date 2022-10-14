
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  WARNING: EXPERIMENTAL TESTS - THIS IS STANDALONE FROM GLORIA.
// Doesnt Work very well yet...
/////////////////////////////////////////////////////////////////////////////////////////////////////

// CONTROLS
$h = 5;                      // Time (in Hours) to look back and Make Average Prices from
$buyIfDropsBelow  = 0.0025;  // Trigger buy if price falls % below average
$buyIfVolSpikesUp = 0.015;
$sellIfRisesAbove = 0.02;    // Trigger sell if price hits % increase

////////////////////////////////////////////////////////////////////////////////////////////////////

include('config.php');

$db     = new Db();
$prices = new PriceList();

$priceList = $prices->get_all(4500, 'btcusd');
$counter = 1;
$periodCount = 0;
$volumeCount = 0;
$bp = 0;

$totalProfit = 0;

$holding = false;
$tradeArray = array();

foreach (array_reverse($priceList) as $key => $data){

    $sliced_array = array_slice(array_reverse($priceList), $key, 30);

    foreach ($sliced_array as $key2 => $data2){
        $periodCount += $data2['ask_price'];
        $volumeCount += $data2['volume'];
    }

    if(count($sliced_array) == 30){
        $n = $sliced_array[29]['ask_price'];
        $v = $sliced_array[29]['volume'];

        $sma6 = $periodCount/30;
        $volsma = $volumeCount/30;

        if($holding == false){

            if($n < $sma6 - ($sma6 * $buyIfDropsBelow) && $v > $volsma + ($volsma * $buyIfVolSpikesUp)){
                $tradeArray[] = "BUY: ".$sliced_array[29]['ask_price']. " ".$sliced_array[29]['datestamp'];

                $holding = true;
                $bp = $sliced_array[29]['ask_price'];
            }

            $periodCount = 0;
            $volumeCount = 0;

        }else{
            if($sliced_array[29]['bid_price'] > $bp + ($bp * $sellIfRisesAbove)){

                $profit = ((($sliced_array[29]['bid_price'] - $bp) / $sliced_array[29]['bid_price']) * 100) - 0.5;
                $tradeArray[] = "SELL: ".$sliced_array[29]['bid_price']. " ".$sliced_array[29]['datestamp']." (PROFIT ".number_format($profit, 2, '.', '')."%)";

                $holding = false;
                $bp = 0;
                $totalProfit += $profit;

            }
        }
    }
}

echo "total profits: ".number_format($totalProfit, 2, '.', '')."%<br/><br/>";
print_r($tradeArray);