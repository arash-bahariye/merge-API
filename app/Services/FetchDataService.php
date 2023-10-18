<?php

namespace App\Services;

use GuzzleHttp\Client;

class FetchDataService{
    public static function fetchData(String $url, Array $query = NULL){
        $client = new Client();
        $response = $client->get($url, [
            'query' => $query
        ]);

        return json_decode($response->getBody(), true);
    }
}