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
                "last_price" => $price['last_price'],
                "ask_price"  => $price['ask_price'],
                "bid_price"  => $price['bid_price'],
                "volume"     => $price['volume'],
                "sma50"      => $this->getSMA($array, $key, 50  * $SMAMultiplier),
                "sma200"     => $this->getSMA($array, $key, 200 * $SMAMultiplier),
                "ema12"      => $ema12,
                "ema26"      => $ema26,
                "signal"     => $signal,
                "macd"       => $macd,
                "divergence" => $macd - $signal,
                "vol_sma50"  => $this->getVolSMA($array, $key, 18),
            );
        }

        return $completeData;
    }

    function getSMA($array, $key, $period){

        if($key >= $period - 1){
            $sma = array_slice($array, ($key - $period) + 1, $period, true);
            $smaArray = array();

            foreach ($sma as $n => $v) {
                $smaArray[] = $v['last_price'];
            }

            return array_sum($smaArray) / $period;
        }else{
            return false;
        }
    }

    function getVolSMA($array, $key, $period){

        if($key >= $period - 1){
            $sma = array_slice($array, ($key - $period) + 1, $period, true);
            $smaArray = array();

            foreach ($sma as $n => $v) {
                $smaArray[] = $v['volume'];
            }

            return array_sum($smaArray) / $period;
        }else{
            return false;
        }
    }

    function getEMA9($array, $key, $period, $lastPrice){

        if($key == $period - 1){

            $sma = array_slice($array, ($key - $period) + 1, $period, true);
            $smaArray = array();

            foreach ($sma as $n => $v) {
                $smaArray[] = $v['last_price'];
            }

            $this->set_lastEMA9(array_sum($smaArray) / $period);

            return array_sum($smaArray) / $period;

        }else if($key >= $period){

            $multiplier = 2/($period + 1);
            $ema = ($lastPrice - $this->get_lastEMA9()) * $multiplier + $this->get_lastEMA9();

            $this->set_lastEMA9($ema);

            return $ema;

        }else{
            return false;
        }
    }

    function getEMA12($array, $key, $period, $lastPrice){

        if($key == $period - 1){

            $sma = array_slice($array, ($key - $period) + 1, $period, true);
            $smaArray = array();

            foreach ($sma as $n => $v) {
                $smaArray[] = $v['last_price'];
            }

            $this->set_lastEMA12(array_sum($smaArray) / $period);

            return array_sum($smaArray) / $period;

        }else if($key >= $period){

            $multiplier = 2/($period + 1);
            $ema = ($lastPrice - $this->get_lastEMA12()) * $multiplier + $this->get_lastEMA12();

            $this->set_lastEMA12($ema);

            return $ema;

        }else{
            return false;
        }
    }

    function getEMA26($array, $key, $period, $lastPrice){

        if($key == $period - 1){

            $sma = array_slice($array, ($key - $period) + 1, $period, true);
            $smaArray = array();

            foreach ($sma as $n => $v) {
                $smaArray[] = $v['last_price'];
            }

            $this->set_lastEMA26(array_sum($smaArray) / $period);

            return array_sum($smaArray) / $period;

        }else if($key >= $period){

            $multiplier = 2/($period + 1);
            $ema = ($lastPrice - $this->get_lastEMA26()) * $multiplier + $this->get_lastEMA26();

            $this->set_lastEMA26($ema);

            return $ema;

        }else{
            return false;
        }
    }

    function getSignal($array, $key, $period, $lastSignal){

        if($key == 33){

            $sma = array_slice($array, ($key - $period) + 1, 9, true);
            $smaArray = array();

            foreach ($sma as $n => $v) {
                $smaArray[] = $v['ema12'] - $v['ema26'];
            }

            $smaArray[] = $lastSignal;

            $this->set_lastEMA9(array_sum($smaArray) / $period);

            return array_sum($smaArray) / $period;

        }else if($key > 33){

            $multiplier = 2/($period + 1);
            $ema = ($lastSignal - $this->get_lastEMA9()) * $multiplier + $this->get_lastEMA9();

            $this->set_lastEMA9($ema);

            return $ema;

        }else{
            return false;
        }
    }
}