<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - MOVING AVERAGES
/////////////////////////////////////////////////////////////////////////////////////////////////////

class MovingAverages{
    var $lastEMA9;
    var $lastEMA12;
    var $lastEMA26;

    function __construct($lastEMA9=NULL, $lastEMA12=NULL, $lastEMA26=NULL) {
        $this->set_lastEMA9($lastEMA9);
        $this->set_lastEMA12($lastEMA12);
        $this->set_lastEMA26($lastEMA26);
    }

    function get_lastEMA9() {
        return $this->lastEMA9;
    }
    function get_lastEMA12() {
        return $this->lastEMA12;
    }
    function get_lastEMA26() {
        return $this->lastEMA26;
    }

    function set_lastEMA9($lastEMA9) {
        $this->lastEMA9 = $lastEMA9;
    }
    function set_lastEMA12($lastEMA12) {
        $this->lastEMA12 = $lastEMA12;
    }
    function set_lastEMA26($lastEMA26) {
        $this->lastEMA26 = $lastEMA26;
    }

    function build_averages($array, $SMAMultiplier){

        $completeData = array();

        foreach ($array as $key => $price){

            $ema12 = $this->getEMA12($array, $key, 12, $price['last_price']);
            $ema26 = $this->getEMA26($array, $key, 26, $price['last_price']);

            $macd = $ema12 - $ema26;
            $signal = $this->getSignal($completeData, $key, 9, $macd);

            $completeData[] = array(
                "datestamp"  => $price['datestamp'],
             