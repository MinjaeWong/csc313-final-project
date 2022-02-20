
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - PRICE LIST
/////////////////////////////////////////////////////////////////////////////////////////////////////

class PriceList{
    var $datestamp;
    var $lastPrice;
    var $bidPrice;
    var $askPrice;
    var $volume;
    var $currencyPair;

    function __construct($datestamp=NULL, $lastPrice=NULL, $bidPrice=NULL, $askPrice=NULL, $volume=NULL, $currencyPair=NULL) {
        $this->set_datestamp($datestamp);
        $this->set_lastPrice($lastPrice);
        $this->set_bidPrice($bidPrice);
        $this->set_askPrice($askPrice);
        $this->set_volume($volume);
        $this->set_currencyPair($currencyPair);
    }

    function get_datestamp() {
        return $this->datestamp;
    }
    function get_lastPrice() {
        return $this->lastPrice;
    }
    function get_bidPrice() {
        return $this->bidPrice;
    }
    function get_askPrice() {
        return $this->askPrice;
    }
    function get_volume() {
        return $this->volume;
    }
    function get_currencyPair() {
        return $this->currencyPair;
    }

    function set_datestamp($datestamp) {
        $this->datestamp = $datestamp;
    }
    function set_lastPrice($lastPrice) {
        $this->lastPrice = $lastPrice;
    }
    function set_bidPrice($bidPrice) {
        $this->bidPrice = $bidPrice;
    }
    function set_askPrice($askPrice) {
        $this->askPrice = $askPrice;
    }
    function set_volume($volume) {
        $this->volume = $volume;
    }
    function set_currencyPair($currencyPair) {
        $this->currencyPair = $currencyPair;
    }

    function save($master = false){

        $db = new Db();

        $datestamp       = $db->quote($this->datestamp);
        $lastPrice       = $db->quote($this->lastPrice);
        $bidPrice        = $db->quote($this->bidPrice);
        $askPrice        = $db->quote($this->askPrice);
        $volume          = $db->quote($this->volume);
        $currencyPair    = $db->quote($this->currencyPair);

        $db->query("
            INSERT INTO prices" . ($master ? "_bitstamp" : "") . "(
                datestamp,
                last_price,
                bid_price,
                ask_price,
                volume".
                ($master ? ",currency_pair" : "") .
                ")
            VALUES(
                '$datestamp',
                '$lastPrice',
                '$bidPrice',
                '$askPrice',
                '$volume'".
                ($master ? ",'$currencyPair'" : "").
            ")
        ");
    }

    function get_all($limit, $currency){

        $db = new Db();
        $sql = $db->query("SELECT * FROM prices_bitstamp WHERE currency_pair = '$currency' ORDER BY datestamp DESC ".($limit ? 'LIMIT '.$limit : ''));

        if($sql === false) {
            return false;
        }

        $priceList = array();

        while ($row = $sql->fetch_assoc()) {
            $priceList[] = array("datestamp" => $row["datestamp"], "last_price" => $row["last_price"], "bid_price" => $row["bid_price"], "ask_price" => $row["ask_price"], "volume" => $row["volume"]);
        }

        if(!$limit){
            $limit = count($priceList);
        }

        if(count($priceList) == $limit){
            return $priceList;
        }else{
            return false;
        }
    }

    function get_graphData($limit, $currency){
        $db = new Db();
        $sql = $db->query("SELECT * FROM prices_bitstamp WHERE currency_pair = '$currency' ORDER BY datestamp DESC ".($limit ? 'LIMIT '.$limit : ''));

        if($sql === false) {
            return false;
        }

        $dates = array();
        $last  = array();
        $bid   = array();
        $ask   = array();

        while ($row = $sql->fetch_assoc()) {
            $dates[] = $row["datestamp"];
            $last[] = $row["last_price"];
            $bid[] = $row["bid_price"];
            $ask[] = $row["ask_price"];
        }
        return array("labels" => array_reverse($dates), "last" => array_reverse($last), "bid" => array_reverse($bid), "ask" => array_reverse($ask));
    }

    function get_lastChange($currency){
        $db = new Db();
        $sql = $db->query("SELECT last_price, datestamp FROM prices_bitstamp WHERE currency_pair = '$currency' ORDER BY datestamp DESC LIMIT 2");

        if($sql === false) {
            return false;
        }

        $prices = array();
