
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