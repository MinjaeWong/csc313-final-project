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
                "MA