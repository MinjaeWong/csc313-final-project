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
        $req['signature'] = strtoupper(hash_hmac('sha256', $req['nonce'] . $this->clientId . $this->key, $this->secret));

        // generate the POST data string
        $post_data = http_build_query($req, '', '&');

        // our curl handle
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.bitstamp.net/api/v2/' . $path .'/',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => 'Bitstamp Data',
            CURLOPT_SSL_VERIFYPEER => LIVE_ENVIRONMENT ? 1 : 0, // Enable SSL if in live enviroment
            CURLOPT_SSL_VERIFYHOST => 2
        ));

        if ($verb == 'post')
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        // run the query
        $resp = curl_exec($curl);
        if ($resp === false)
            throw new \Exception('Could not get reply: ' . curl_error($curl));

        $dec = json_decode($resp, true);

        if (is_null($dec))
            throw new \Exception('Invalid data received, please make sure connection is working and requested API exists');

        return $dec;
    }

    function get_data() {
        return $this->bitstamp_query("ticker/{$this->currencyPair}", array(), 'get');
    }

    function get_balance() {
        return $this->bitstamp_query('balance');
    }

    function buyMarketOrder($amount) {
        return $this->bitstamp_query("buy/market/{$this->currencyPair}", ['amount' => $amount]);
    }

    function sellMarketOrder($amount) {
        return $this->bitstamp_query("sell/market/{$this->currencyPair}", ['amount' => $amount]);
    }

    function openOrders() {
        return $this->bitstamp_query('open_orders/all');
    }

    function userTransactions() {
        return $this->bitstamp_query('user_transactions');
    }
}
