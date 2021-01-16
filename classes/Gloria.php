
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