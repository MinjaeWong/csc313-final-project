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

        $this->key = BITSTAMP_KEY;
        $this->secret = BITSTAMP_SECRET;
        $this->clientId = BITSTAMP_CLIENTID;

        if ($this->key == '' || $this->secret == ''  || $this->clientId == '')
            throw new \Exception('BitStamp Key, Secrect and Client ID need to be set');
    }

    function get_currencyPair() {
        return $this->currency