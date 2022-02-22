
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - TRADE
/////////////////////////////////////////////////////////////////////////////////////////////////////

class Trade{

    function __construct() {
    }

    function get_lastTrade(){
        $db = new Db();

        $sql = $db -> query("SELECT * FROM trades ORDER BY id DESC LIMIT 1");

        if($sql === false) {
            return false;
        }

        $holding = false;

        while ($row = $sql->fetch_assoc()) {
            if($row['buy_date'] > 0 && $row['sell_date'] == 0){
                $holding = true;
            }
            return array("buy_price" => $row['buy_order_price'], "holding" => $holding);
        }

        return false;
    }

    function get_allTrades(){
        $db = new Db();

        $sql = $db -> query("SELECT * FROM trades ORDER BY id ASC");

        if($sql === false) {
            return false;
        }

        $totalProfit = 0;
        $firstBuy = NULL;

        echo "<p><span class='white'>Project Gloria.</span></p><p>An Autonomous-Trading Cryptocurrency Algorithm.</p><br/><p>Currency: <span class='blue'>Bitcoin (BTC) / USD</span></p><p>Exchange: <span class='blue'>Bitstamp</span></p><p>Trade Fee: <span class='blue'>0.25%</span></p><br/>";

        while ($row = $sql->fetch_assoc()) {

            if($firstBuy == NULL){
                $firstBuy = $row['buy_date'];
            }

            $buyValue  = $row['buy_value'];
            $buyDate   = $row['buy_date'];
            $sellDate  = $row['sell_date'];

            if($row['buy_order_price']){
                $buyPrice  = $row['buy_order_price'];
            }else{
                $buyPrice = $row['buy_price'];
            }

            if($row['buy_order_price']){
                $sellPrice = $row['sell_order_price'];
            }else{
                $sellPrice = $row['sell_price'];
            }

            echo "<p>[$buyDate] <span class='blue'>'BUY'</span> &nbsp;: <span class='pink'>$$buyPrice</span></p>";

            if($sellDate >0) {
                $g = new Gloria();
                $profit = $g->calculateProfitPercentage($buyValue, $buyPrice, $sellPrice);
                $profitPerc = number_format($profit, 2, '.', '');
                $totalProfit += $profit;
                echo "<p>[$sellDate] <span class='blue'>'SELL'</span> : <span class='pink'>$$sellPrice</span></p>";
                echo "<p>[$sellDate] <span class='".($profitPerc > 0 ? 'green' : 'red')."'>'PROFIT'</span> : <span class='".($profitPerc > 0 ? 'green' : 'red')."'>".($profitPerc > 0 ? '+' : '')."$profitPerc%</span></p><br/>";
            }
        }

        $date1 = new DateTime($firstBuy);
        $date2 = new DateTime();
        $dateDiff = $date1->diff($date2);
        $dateDiff = $dateDiff->days;

        echo "<br/><p>";
        echo "<span class='blue'>Profit Total (Last $dateDiff Days):</span> <span class='".(number_format($totalProfit, 2, '.', '') > 0 ? 'green' : 'red')."'>".(number_format($totalProfit, 2, '.', '') > 0 ? '+' : '').number_format($totalProfit, 2, '.', '')."%</span><br/>";
        echo "<span class='blue'>Profit-Per-Day:</span> <span class='".(number_format(($totalProfit / $dateDiff), 2, '.', '') > 0 ? 'green' : 'red')."'>".(number_format(($totalProfit / $dateDiff), 2, '.', '') > 0 ? '+' : '').number_format(($totalProfit / $dateDiff), 2, '.', '')."%</span>";
        echo "</p>";

        return true;
    }

    function calculateTradeProfit($buyPrice, $sellPrice, $tradeFee){

        $lastTradeBuyFee  = $buyPrice  * $tradeFee;
        $lastTradeSellFee = $sellPrice * $tradeFee;
        $totalFees        = $lastTradeBuyFee + $lastTradeSellFee;
        $lastTradeNet   = $sellPrice - $buyPrice - $totalFees;

        return ($lastTradeNet / $buyPrice) * 100;
    }

    function get_profitReport(){
