<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - CONFIG
//
//  A quick solution to allow for different environment variables and keep keys/passwords
//  out of the repo.
//
//  Copy this example file in the same root directory, remove .example (ie. env.php) and
//  change the constants to the specific environment. This is a one off when cloning the repo
//  and the specific env.php file will not commit as it's in .gitignore
/////////////////////////////////////////////////////////////////////////////////////////////////////

// App
define('BASE_URL', 'https://projectgloria.xyz/');
define('LIVE_ENVIRONMENT', FALSE);
define('LIVE_ORDERS', FALSE);
define('FIXED_TRADE_AMOUNT', 100);
define('GET_CURRENCY_PAIRS', ['btcusd', 'xrpusd', 'ltcusd', 'ethusd', 'bchusd']);

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'projectgloria_btc');
define('DB_USER', 'root');
define('DB_PASSWORD', '123');

/*
 * Bitstamp
 *
 * API Key and secret: https://www.bitstamp.net/account/security/api/
 * Client ID: https://www.bitstamp.net/account/balance/
 *
 */

define('BITSTAMP_KEY', 'key');
define('BITSTAMP_SECRET', 'secret');
define('BITSTAMP_CLIENTID', 'client');

// OneSignal
define('ONESIGNAL_APP_ID', '');
define('ONESIGNAL_APP_KEY', '');
