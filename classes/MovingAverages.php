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
        