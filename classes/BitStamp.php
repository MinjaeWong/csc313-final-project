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
        return $this->currencyPair;
    }

    function set_currencyPair($currencyPair) {
        $this->currencyPair = $currencyPair;
    }

    public function bitstamp_query($path, $req = [], $verb = 'post') {

        // generate a nonce as microtime, with as-string handling to avoid problems with 32bits systems
        $mt = explode(' ', microtime());
        $req['nonce'] = $mt[1] . substr($mt[0], 2, 6);
        $req['key'] = $this->key;
        $req['signature'] = strtoupper(hash_hmac('sha256', $req['nonce'] . $th