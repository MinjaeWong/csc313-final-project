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
define('