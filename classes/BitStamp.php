<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - BITSTAMP Account Api For Balances, Buying & Selling etc.
//  Could possibly elaborate on this and develop for multiple services that allow API trading
/////////////////////////////////////////////////////////////////////////////////////////////////////


class BitStamp {
    private $key;
    private $secret;
    private $clientId;
    public $currencyPair;

    function __construct($currencyPair = 'btcusd') {
        $this->set_currencyPair($currencyPair);

        $this->key = 