
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