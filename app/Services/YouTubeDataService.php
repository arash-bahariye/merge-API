<?php

namespace App\Services;

class YouTubeDataService
{
    public static function getPopularVideos()
    {
        $countries = array("GB", "NL", "DE", "FR", "ES", "IT", "GR");
        $url = "https://www.googleapis.com/youtube/v3/videos";
        $data = array();
        $query = [
            'part' => 'snippet',
            'chart' => 'mostPopular',
            'maxResults' => 40,
            'key' => env("YOUTUBE_API_KEY"),
        ];
        foreach ($countries as $country) {
            $query['regionCode'] = $country;
            $rawData = FetchDataService::fetchData($url, $query);
            $trimmedData = array();
            foreach ($rawData['items'] as $item){
                $videoDescription = $item['snippet']['description'];
                $videoThumbnails = [
                    "medium_resolution" => $item["snippet"]["thumbnails"]["medium"],
                    "high_resolution" => $item["snippet"]["thumbnails"]["high"]
                ];
                array_push($trimmedData, [
                    "video_description" => $videoDescription,
                    "video_thumbnails" => $videoThumbnails,
                ]);
            }
            $data[$country] = $trimmedData;
        }
        return $data;
    }
}