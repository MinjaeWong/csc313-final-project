<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - CONFIG
/////////////////////////////////////////////////////////////////////////////////////////////////////

// Set Timezone
date_default_timezone_set('Australia/Brisbane');

// Configuration Settings (WARNING!!!! Changing these affects the algorithm on LIVE & SIMULATION mode)
function coinConfig($currency){
    switch ($currency) {

        case "btcusd":
            return array(
                "SMAMultiplier"   => 2.88,  // 50 / 200 Period SMA Multiplier (2.88 = 1 Day / 4 Days)
                "MACDBuyOffset"   => 27,    // To buy, MACD + Offset must be < Previous MACD
                "MACDSellOffset"  => 20,    // To Sell, MACD must be > Offset
                "MACDMinimum"     => -20,   // To buy, MACD must be < Minimum
                "VolAvMultiplier" => 0.02   // % of Average Volume above current volume required to buy
            );
            break;

        case "ltcusd":
            return array(
                "SMAMultiplier"   => 2.88,  // 50 / 200 Period SMA Multiplier (2.88 = 1 Day / 4 Days)
                "MACDBuyOffset"   => 0.5,   // To buy, MACD + Offset must be < Previous MACD
                "MACDSellOffset"  => 0.3,   // To Sell, MACD must be > Offset
                "MACDMinimum"     => 0,   // To buy, MACD must be < Minimum
                "VolAvMultiplier" => 0.02  // % of Average Volume above current volume required to buy
            );
            break;

        case "ethusd":
            return array(
                "SMAMultiplier"   => 2.88,  // 50 / 200 Period SMA Multiplier (2.88 = 1 Day / 4 Days)
                "MACDBuyOffset"   => 3,     // To buy, MACD + Offset must be < Previous MACD
                "MACDSellOffset"  => 1,     // To Sell, MACD must be > Offset
                "MACDMinimum"     => 0,   // To buy, MACD must be < Minimum
                "VolAvMultiplier" => 0.02  // % of Average Volume above current volume required to buy
            );
            break;

        case "xrpusd":
            return array(
                "SMAMultiplier"   => 2.88,    // 50 / 200 Period SMA Multiplier (2.88 = 1 Day / 4 Days)
                "MACDBuyOffset"   => 0.0005,  // To buy, MACD + Offset must be < Previous MACD
                "MACDSellOffset"  => 0.00205, // To Sell, MACD must be > Offset
                "MACDMinimum"     => 0,   // To buy, MACD must be < Minimum
                "VolAvMultiplier" => 0.02    // % of Average Volume above current volume required to buy
            );
            break;

        case "bchusd":
            return array(
                "SMAMultiplier"   => 2.88,  // 50 / 200 Period SMA Multiplier (2.88 = 1 Day / 4 Days)
                "MACDBuyOffset"   => 5,     // To buy, MACD + Offset must be < Previous MACD
                "MACDSellOffset"  => 3,     // To Sell, MACD must be > Offset
                "MACDMinimum"     => 0,   // To buy, MACD must be < Minimum
                "VolAvMultiplier" => 0.02  // % of Average Volume above current volume required to buy
            );
            break;
    }

    return false;
}

// Load Environment Const