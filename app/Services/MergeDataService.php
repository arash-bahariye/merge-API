<?php

namespace App\Services;

class MergeDataService
{
    public static function mergerYouTubeWiki(Array $youtube, Array $wiki){
        $mergedData = array();
        foreach($wiki as $key=>$val)
        {
            $mergedData[$key] = array_merge(['popular_youtube_videos' => $youtube[$key]], ['country_description' => $val]);
        }
        return $mergedData;
    }
}