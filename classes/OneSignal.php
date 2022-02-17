
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - ONESIGNAL, For push notifications of Automated Buys & Sells
/////////////////////////////////////////////////////////////////////////////////////////////////////

class OneSignal{
    private $appId;
    private $apiKey;

    function __construct() {
        $this->appId = ONESIGNAL_APP_ID;
        $this->apiKey = ONESIGNAL_APP_KEY;

        if ($this->appId == '' || $this->apiKey == '')
           throw new \Exception('OneSignal App ID and Key need to be set');

    }

    function sendNotification($message, $title = null, $url = BASE_URL, $segments = ['All']) {
        $fields = [
            'app_id' => $this->appId,
            'included_segments' => $segments,
            'contents' => [
                'en' => $message
            ],
            'url' => $url,
            'chrome_web_icon' => BASE_URL . '/img/gloria-logo-lg.png',
            'chrome_web_badge' => BASE_URL . '/img/gloria-logo-white-sm.png'
        ];

        if (isset($title))
            $fields['headings'] = ['en' => $title];

        $fields = json_encode($fields);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_SSL_VERIFYPEER => LIVE_ENVIRONMENT ? 1 : 0, // Enable SSL if in live enviroment,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_URL => 'https://onesignal.com/api/v1/notifications',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic ' . $this->apiKey
            ]
        ]);