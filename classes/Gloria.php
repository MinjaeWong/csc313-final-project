
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - Here she is... <3
/////////////////////////////////////////////////////////////////////////////////////////////////////

class Gloria{
    var $divergence;
    var $buyPrice;
    var $macdPrev;
    var $holding;
    var $profit;
    var $firstBuy;
    var $lastSell;

    function __construct($divergence=NULL, $buyPrice=NULL, $macdPrev=NULL, $holding=FALSE, $profit=NULL, $firstBuy=NULL, $lastSell=NULL) {
        $this->set_divergence($divergence);
        $this->set_buyPrice($buyPrice);
        $this->set_macdPrev($macdPrev);
        $this->set_holding($holding);
        $this->set_profit($profit);
        $this->set_firstBuy($firstBuy);
        $this->set_lastSell($lastSell);
    }

    function get_divergence() {
        return $this->divergence;
    }
    function get_buyPrice() {
        return $this->buyPrice;
    }
    function get_macdPrev() {
        return $this->macdPrev;
    }
    function get_holding() {
        return $this->holding;
    }
    function get_profit() {
        return $this->profit;
    }
    function get_firstBuy() {
        return $this->firstBuy;
    }
    function get_lastSell() {
        return $this->lastSell;
    }

    function set_divergence($divergence) {
        $this->divergence = $divergence;
    }
    function set_buyPrice($divergence) {
        $this->buyPrice = $divergence;
    }
    function set_macdPrev($macdPrev) {
        $this->macdPrev = $macdPrev;
    }
    function set_holding($holding) {
        $this->holding = $holding;
    }
    function set_profit($profit) {
        $this->profit = $profit;
    }
    function set_firstBuy($firstBuy) {
        $this->firstBuy = $firstBuy;
    }
    function set_lastSell($lastSell) {
        $this->lastSell = $lastSell;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //  FUNCTIONS
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    function buy($key, $lastKey, $price, $date, $mode, $macd){

        // SIM MODE
        if($mode == "simulation") {
                echo "<p style='margin: 0; background:#f7f41f; border-bottom: 1px solid #000;'>[BUY DATE] $date [BUY PRICE] ".number_format($price, 2, '.', '')." [MACD] $macd</p>";
                $this->set_buyPrice($price);
                $this->set_holding(TRUE);

                if ($this->get_firstBuy() == NULL) {
                    $this->set_firstBuy($date);
                }

        // LIVE MODE
        }else if($mode == "live") {
            if($key == $lastKey) {
                // Save Buy to DB & make live order
                $trade = new Trade();
                $trade->buy(FIXED_TRADE_AMOUNT, $price, $date);
            }
        }
        return;
    }

    function sell($sellPrice, $date){
        // Save Sell to DB & make live order
        $trade = new Trade();
        $trade->sell($sellPrice, $date);
    }

    function get_LiveHolding(){
        // Check DB Holding
        $holding = new Trade();
        $holding = $holding->get_lastTrade();

        if($holding['holding']){
            $this->set_buyPrice($holding['buy_price']);
            $this->set_holding(TRUE);
        }
    }

    function changeInPercent($a, $b){
        $current = $a;
        $previous= $b;
        $diff = $current - $previous;
        $more_less = $diff > 0 ? "+" : "-";
        $diff = abs($diff);
        $percentChange = ($diff/$previous)*100;
        return $more_less.number_format($percentChange, 2, '.', '');
    }

    function get_profitReport(){
        $date1 = new DateTime($this->get_firstBuy());
        $date2 = new DateTime();
        $dateDiff = $date1->diff($date2);
        $dateDiff = $dateDiff->days;

        echo "<h3>";
        echo "<span>Profit (Last $dateDiff Days):</span><span style='color:".(number_format($this->get_profit(), 2, '.', '') > 0 ? '#48F741' : '#f74141').";'> ".(number_format($this->get_profit(), 2, '.', '') > 0 ? '+' : '').number_format($this->get_profit(), 2, '.', '')."%</span><br/>
              <span>Profit-Per-Day:</span><span style='color:".(number_format(($this->get_profit() / $dateDiff), 2, '.', '') > 0 ? '#48F741' : '#f74141').";'> ".(number_format(($this->get_profit() / $dateDiff), 2, '.', '') > 0 ? '+' : '').number_format(($this->get_profit() / $dateDiff), 2, '.', '')."%</span>";
        echo "</h3>";
    }

    function calculateProfitPercentage($amount, $buyPrice, $sellPrice) {
        $coinsHeld = $amount / $buyPrice;
        $coinsHeld = $coinsHeld - ($coinsHeld * 0.0025);
        $coinsSold = $coinsHeld - ($coinsHeld * 0.0025);
        $profit = (($coinsSold * $sellPrice) - $amount) / $amount * 100;

        return $profit;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //  THE ALGORITHM, The Heart and soul of our Gal
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    function run($data, $config, $mode){

        // Get Key of Last Item (Only trade on latest Scrape)
        $lastKey = count($data) - 1;

        if($mode == "live") {
            // Check if holding BTC
            $this->get_LiveHolding();
        }

        foreach ($data as $key => $val){

            if($this->get_divergence()){

                // MACD Sell Trigger
                if($this->get_divergence() > 0 && $val['divergence'] < 0){

                    $date = $val['datestamp'];
                    $macd = $val['macd'];
                    $sellPrice = $val['bid_price'];
                    $buyPrice = $this->get_buyPrice();
                    $feeOffset = $buyPrice * 0.005;
                    $buyPriceIncludingFee = $buyPrice + $feeOffset;

                    if($buyPrice > 0){

                        /////////////////////////////////////////////////////////////////////////////////////////////////////
                        //  MAIN SELL ALGORITHM
                        /////////////////////////////////////////////////////////////////////////////////////////////////////

                        // Failsafe to minimise losses.
                        $failsafeSell = false;

                        if ($this->changeInPercent($val['bid_price'], $buyPriceIncludingFee) < -6)
                            $failsafeSell = true;

                        if((($val['bid_price'] > $buyPriceIncludingFee && $macd > $config['MACDSellOffset']) || $failsafeSell) && $this->get_holding()){

                            // SIM MODE
                            if($mode == "simulation"){
                                    $profit = $this->calculateProfitPercentage(FIXED_TRADE_AMOUNT, $buyPrice, $sellPrice);
                                    $profitPerc = number_format($profit, 2, '.', '');

                                    echo "<p style='margin: 0; border-bottom: 1px solid #000; background:".($profitPerc > 0 ? '#48F741' : '#f74141').";'>[SELL DATE] $date [SELL PRICE] ".number_format($sellPrice, 2, '.', '')." [PROFIT] $profitPerc % [MACD] $macd</p>";
                                    $this->set_holding(FALSE);

                                    $totalProfit = $this->get_profit() + $profit;
                                    $this->set_profit($totalProfit);
                                    $this->set_lastSell($date);

                            // LIVE MODE
                            }else if($mode == "live") {
                                // Commit Sell
                                if($key == $lastKey) {
                                    $this->sell($sellPrice, $date);
                                }