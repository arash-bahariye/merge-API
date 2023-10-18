<?php

namespace App\Services;

class WikipediaDataService
{
    public static function getCountryDescription()
    {
        $url = "https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro&explaintext&redirects=1&titles=";
        $data = array();

        $countries = [
            "GB" => "united kingdom",
            "NL" => "netherlands", 
            "DE" => "germany", 
            "FR" => "france", 
            "ES" => "spain", 
            "IT" => "italy", 
            "GR" => "greece"
        ];

        foreach ($countries as $key => $country) {
            $response = FetchDataService::fetchData($url.urlencode($country));
            $pages = $response['query']['pages'];
            $pageId = array_keys($pages)[0];
            $data[$key] = [$pages[$pageId]['extract']];
        }

        return $data;
    }
}