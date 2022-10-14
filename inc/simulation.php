
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - SIMULATION TEST BED to debug and refine the Algorithm
/////////////////////////////////////////////////////////////////////////////////////////////////////

// Config & Class Library
include('config.php');

error_reporting(E_ALL ^ E_WARNING);

$db = new Db();

$currency = $_GET['currencypair'];
$coinConfig = coinConfig($currency);

// Get Price Array
$prices = new PriceList();
$priceList = $prices->get_all(null, $currency); // Set limit number for last period or 'null' for all history
$graphData = $prices->get_graphData(36, $currency); // 6 Hours

// Build Complete Data Set
if($priceList){
    $ma = new MovingAverages();
    $completeData = $ma->build_averages(array_reverse($priceList), $coinConfig['SMAMultiplier']);

    // Run Simulation
    $gloria = new Gloria();
    $gloria->run($completeData, $coinConfig, 'simulation');
    $gloria->get_profitReport();

}else{
    echo "Error fetching price list.";
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <title>GLORIA VERSION 1.1.0</title>

    <style>
        body{
            background: #272727;
        }
        h3{
            color: #929292;
            margin: 15px;
            font-size: 15px;
        }
        p{
            color: #272727;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12" style="height: 400px; margin-top: 30px;">
            <canvas id="btc-canvas-price" style="margin-left: -5px;"></canvas>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script src="https://www.chartjs.org/dist/2.7.2/Chart.bundle.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>

<script>

    var btcPriceConfig = {
        type: 'line',
        data: {
            labels: [<?="'".implode ("', '", $graphData['labels'])."'"?>],
            datasets: [{
                label: 'Last Price',
                fill: false,
                borderWidth: 1,
                pointRadius: 0,
                backgroundColor: '#f740c4',
                borderColor: '#f740c4',
                data: [
                    <?=implode (", ", $graphData['last'])?>
                ]
            },{
                label: 'Bid Price',
                fill: false,
                borderWidth: 1,
                pointRadius: 0,
                backgroundColor: '#f7f41f',
                borderColor: '#f7f41f',
                data: [
                    <?=implode (", ", $graphData['bid'])?>
                ]
            },{
                label: 'Ask Price',
                fill: false,
                borderWidth: 1,
                pointRadius: 0,
                backgroundColor: '#40f7e1',
                borderColor: '#40f7e1',
                data: [
                    <?=implode (", ", $graphData['ask'])?>
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Market Prices - Last 6 Hours (USD)'
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
        window.myLine = new Chart(btcPrice, btcPriceConfig);
    };
</script>
</body>
</html>