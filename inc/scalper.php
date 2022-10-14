
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