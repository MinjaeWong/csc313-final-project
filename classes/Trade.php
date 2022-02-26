
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

        $db = new Db();

        $sql = $db -> query("SELECT * FROM trades WHERE sell_date IS NOT NULL ORDER BY id ASC");

        if($sql === false) {
            return false;
        }

        $tradeList = array();

        while ($row = $sql->fetch_assoc()) {
            $tradeList[] = array(
                "buy_date"   => $row['buy_date'],
                "sell_date"  => $row['sell_date'],
                "buy_price"  => ($row['buy_order_price'] != NULL ? $row['buy_order_price'] : $row['buy_price']),
                "sell_price" => ($row['sell_order_price'] != NULL ? $row['sell_order_price'] : $row['sell_price'])
            );
        }

        // Trade Time (Count from First Buy to Last Sell so averages don't continually drop and spike up again between trades)
        $firstBuy = new DateTime($tradeList[0]['buy_date']);
        $lastSell = new DateTime(end($tradeList)['sell_date']);
        $tradeTime = $firstBuy->diff($lastSell)->days;

        // Last Completed Trade Result
        $lastTradeNetPercent = number_format(($this->calculateTradeProfit(end($tradeList)['buy_price'], end($tradeList)['sell_price'], 0.0025)), 3, '.', '');

        // Last Completed Trade Time Ago
        $lastTradeAgo = new DateTime(end($tradeList)['sell_date']);
        $now = new DateTime();
        $timeAgo = $lastTradeAgo->diff($now)->days." days ago"; // Minutes ago

        // Profit-Per-Day
        $totalProfit = 0;
        foreach($tradeList as $key => $trade){
            $totalProfit += $this->calculateTradeProfit($trade['buy_price'], $trade['sell_price'], 0.0025);
        }
        $profitPerDay = number_format(($totalProfit / $tradeTime), 3, '.', '');

        // Return Array
        return array(
            "profit_per_day" => $profitPerDay,
            "last_trade"     => array(
                "datestamp"  => $timeAgo,
                "profit"     => $lastTradeNetPercent
            )
        );
    }

    function buy($value, $price, $date) {
        $db = new Db();
        $bs = new BitStamp();
        $orderId = 'null';
        $orderAmount = 'null';
        $orderPrice = 'null';

        if (LIVE_ORDERS) {
            $btc = number_format($value/$price, 8);
            $buyOrder = $bs->buyMarketOrder($btc);

            if (isset($buyOrder['id'])) {
                $orderId = "'{$buyOrder['id']}'";
                $orderAmount = $buyOrder['amount'];
                $orderPrice = $buyOrder['price'];
            }
        }

        $db->query("
            INSERT INTO trades(
                buy_value,
                buy_price,
                buy_date,
                buy_order_id,
                buy_order_amount,
                buy_order_price
                )
            VALUES(
                '$value',
                '$price',
                '$date',
                $orderId,
                $orderAmount,
                $orderPrice
            )
        ");

        // Send notification of buy
        $os = new OneSignal();
        $price = $orderPrice == 'null' ? $price : $orderPrice;
        $response = $os->sendNotification("BUY: $$value @ $$price", 'Gloria feels it in the air tonight...');
    }

    function sell($sellPrice, $date){
        $db = new Db();
        $bs = new BitStamp();
        $orderId = 'null';
        $orderAmount = 'null';
        $orderPrice = 'null';

        $sql = $db->query("SELECT * FROM trades WHERE sell_date IS NULL ORDER BY id DESC LIMIT 1");

        if($sql === false || mysqli_num_rows($sql) == 0) {
            return false;
        }

        while ($row = $sql->fetch_assoc()) {
            $tradeId = $row['id'];
            $buyPrice = $row['buy_price'];
            $buyValue = $row['buy_value'];
            $buyDate = $row['buy_date'];
            $buyOrderAmount = $row['buy_order_amount'];
            $buyOrderPrice = $row['buy_order_price'];
        }

        if (LIVE_ORDERS) {
            if (!empty($buyOrderAmount)) {
                $sellOrder = $bs->sellMarketOrder($buyOrderAmount);

                if (isset($sellOrder['id'])) {
                    $orderId = "'{$sellOrder['id']}'";
                    $orderAmount = $sellOrder['amount'];
                    $orderPrice = $sellOrder['price'];
                }
            }
        }

        $db->query("
            UPDATE trades
            SET
                sell_price = '$sellPrice',
                sell_date = '$date',
                sell_order_id = $orderId,
                sell_order_amount = $orderAmount,
                sell_order_price = $orderPrice
            WHERE
                id = '$tradeId'
        ");

        // Send notification of sale
        $os = new OneSignal();
        $gloria = new Gloria();
        $sellPrice = $orderPrice == 'null' ? $sellPrice : $orderPrice;
        $buyPrice = empty($buyOrderPrice) ? $buyPrice : $buyOrderPrice;
        $profit = $gloria->calculateProfitPercentage($buyValue, $buyPrice, $sellPrice);
        $profitPerc = ($profit > 0 ? '+' : '') . number_format($profit, 2, '.', '') . '%';
        $os->sendNotification("SELL: $$sellPrice PROFIT: $profitPerc", 'Ka Ching! Crypto\'s gon Crypt.');
    }
}