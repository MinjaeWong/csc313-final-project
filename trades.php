<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - Trade Activity
/////////////////////////////////////////////////////////////////////////////////////////////////////

// Config & Class Library
require_once('../env.php');
require_once('classes/Db.php');
require_once('classes/Gloria.php');
require_once('classes/Trade.php');

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <title>GLORIA VERSION 1.1.0</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Source+Code+Pro:200,300,400');
        body{
            font-family: 'Source Code Pro', monospace;
            background: #302c38;
            color: #756d86;
            font-weight: 300;
            font-size:12px;
            margin: 20px;
        }
        p{
            margin-top: 0;
            margin-bottom: 3px;
        }
        .white{
            color: #fff;
        }
        .green{
            color: #0f0;
        }
        .blue{
            color: #00ccff;
        }
        .pink{
            color: #ff1de0;
        }
        .red{
            color: #f30;
        }
    </style>

    <link rel="manifest" href="manifest.json" />
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "<?= ONESIGNAL_APP_ID ?>",
            });
        });
    </script>
</head>
<body>
<img src="/img/gloria-logo-lg.png" style="width: 80px;margin-bottom: 20px;" />
<?php $trade = new Trade(); $tradeList = $trade->get_allTrades(); ?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo