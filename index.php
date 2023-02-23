<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - Simple Dashboard to display recent automated trades, prices etc.
/////////////////////////////////////////////////////////////////////////////////////////////////////

// Config & Class Library
require_once('../env.php');
require_once('classes/Db.php');
require_once('classes/PriceList.php');
require_once('classes/BitStamp.php');
require_once('classes/Trade.php');

$db = new Db();

// Get Price Data
$prices = new PriceList();
$graphData = $prices->get_graphData(72, 'btcusd');
$lastPrice = $prices->get_lastChange('btcusd');

// Get Account Data
$bs = new BitStamp();
$accountValue = $bs->get_balance();
$accountValueUSD = number_format(($accountValue['btc_balance'] * $lastPrice['last_price']) + $accountValue['usd_balance'], 2, '.', '');

// Get Trade Data
$profit = new Trade();
$profitReport = $profit->get_profitReport();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="css/gloria.min.css">
    <title>Gloria</title>
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
<div id="display">
    <div class="block-container">
        <div class="block">
            <h2>$<?=$accountValueUSD?></h2>
            <p>Account Value</p>
        </div>
        <div class="block">
            <h3>$<?=number_format($lastPrice['last_price'], 2, '.', ',')?></h3>
            <p>Last Price (<?=$lastPrice['datestamp']?>)</p>
        </div>
        <div class="block">
            <h3><?=($profitReport['last_trade']['profit'] > 0 ? '+' : '').$profitReport['last_trade']['profit']?>%</h3>
            <p>Last Trade Profit (<?=$profitReport['last_trade']['datestamp']?>)</p>
        </div>
        <div class="block">
            <h3><?=($profitReport['profit_per_day'] > 0 ? '+' : '').$profitReport['profit_per_day']?>%</h3>
            <p>Average Daily Profit</p>
        </div>
        <div class="block last">
            <h3><?=(($profitReport['profit_per_day'] * 30.4167) > 0 ? '+' : '').number_format(($profitReport['profit_per_day'] * 30.4167), 3, '.', '')?>%</h3>
            <p>Average Monthly Profit</p>
        </div>
    </div>
</div>
<div id="chart-container">
    <canvas id="btc-canvas-price"></canvas>
</div>
<img id="logo" src="../img/gloria-logo-white-sm.png" />
<span class="version">Gloria Version 1.1.0</span>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script src="https://www.chartjs.org/dist/2.7.2/Chart.bundle.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
<script src="js/gloria.min.js"></script>
<script>
    setTimeout(function(){
            window.location.reload();
    }, 600000);

    var btcPriceConfig = {
        type: 'line',
        data: {
            labels: [<?="'".implode ("', '", $graphData['labels'])."'"?>],
            datasets: [{
                fill: true,
                borderWidth: 1,
                pointRadius: 0,
                backgroundColor: 'rgba(255, 255, 255, 0.1)',
                borderColor: 'rgba(255, 255, 255, 0.0)',
                data: [
                    <?=implode (", ", $graphData['last'])?>
                ]
            }]
        },
        options: {
            elements: {
                line: {
                    tension: 0
                }
            },
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: false
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    ticks: {
                        display: false
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    }
                }],
                yAxes: [{
                    display: true,
                    ticks: {
                        display: false
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    }
                }]
            }
        }
    };
    window.onload = function() {
        var btcPrice = document.getElementById('btc-canvas-price').getContext('2d');
        window.myLine = new Chart(btcPrice, btcPriceConfig, );
    };

</script>
</body>
</html>
