<?php

class currency {
    public $mode = "demo";
    public $baseUrl;
    public $apikey;
    public $apiSecret;
    
    function setMode($mode) {
        $this->mode = $mode;
    }
    
    function setAPIKey($apikey) {
        $this->apikey = $apikey;
    }
    
    function setAPISecret($apiSecret) {
        $this->apiSecret = $apiSecret;
    }
    
    function getHash($querys, $timestamp) {
        $querys['apiKey'] = $this->apikey;
        $querys['timestamp'] = $timestamp;
        $querys = array_reverse($querys);
        $qS = "";
        if (!empty($querys)) {
            foreach ($querys as $querysStaticKey => $queryStatic) {
                $qS .= $querysStaticKey . "=" . $queryStatic . "&";
            }
        }
        $qS = rtrim($qS, "&");
        return hash_hmac('SHA256', $qS, $this->apiSecret);
    }
    
    function request($path, $querys, $method) {
        
        if ($this->mode == "demo") {
            $baseUrl = "https://demo-api-adapter.backend.currency.com/api/v2/";
        } else {
            $baseUrl = "https://api-adapter.backend.currency.com/api/v2/";
        }
        $timestamp = floor(microtime(true) * 1000);
        
        $querysStatic = [
            "timestamp" => $timestamp,
            "signature" => $this->getHash($querys, $timestamp),
        ];
        
        $qS;
        if (!empty($querysStatic)) {
            $qS = "?";
            foreach ($querysStatic as $querysStaticKey => $queryStatic) {
                $qS .= $querysStaticKey . "=" . $queryStatic . "&";
            }
        }
        
        if (!empty($querys)) {
            foreach ($querys as $queryKey => $query) {
                $qS .= "&" . $queryKey . "=" . $query . "&";
            }
        }
        $qS = rtrim($qS, "&");
        $response = file_get_contents($baseUrl . $path . $qS, false, stream_context_create(
            [
                'http' =>
                    [
                        'method'  => $method,
                        'header'  => [
                            "Accept: application/json",
                            "X-MBX-APIKEY: " . $this->apikey
                        ]
                    ]
            ]
        ));
        return $response;
    }
}
