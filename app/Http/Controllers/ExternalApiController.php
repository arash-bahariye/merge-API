<?php

namespace App\Http\Controllers;

use App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ExternalApiController extends Controller
{
    private $cachedData;
    private $input;

    public function __construct(Request $request)
    {
        $this->fetchInitialData();
        $this->input = $request->collect();
    }

    public function index(Request $request)
    {
        if($this->checkPaginateRequest()){
            $this->cachedData = $this->paginate($this->cachedData);
        }
        return response(json_encode($this->cachedData) ,http_response_code(200));
    }
    
    public function show(Request $request, string $country_code)
    {
        $countries = array("GB", "NL", "DE", "FR", "ES", "IT", "GR");
        $country_code = strtoupper($country_code);
        if (in_array($country_code,$countries)){
            logger(true);
            if($this->checkPaginateRequest()){
                logger(true);
                $this->cachedData = [
                    'country_description' => $this->cachedData[$country_code]['country_description'][0],
                    'popular_youtube_videos' => $this->paginate(
                        $this->cachedData[$country_code]['popular_youtube_videos']
                    )
                ];
            }
            return response(json_encode($this->cachedData) ,http_response_code(200));
        }
        return response(json_encode(["errors" => "404 There are no endpoints for the requested country"]), http_response_code(404));
    }

    
    private function fetchInitialData(){
        if (!Cache::has('CachedMergedData')){
            $youtubeData = Services\YouTubeDataService::getPopularVideos();
            $wikiData = Services\WikipediaDataService::getCountryDescription();
            $mergedData = Services\MergeDataService::mergerYouTubeWiki($youtubeData, $wikiData);
            $this->cachedData = Cache::remember('CachedMergedData', 60, function () use ($mergedData){
                return $mergedData;
            });
        } else {
            $this->cachedData = Cache::get('CachedMergedData');
        }
    }

    private function checkPaginateRequest(){
        if (
            isset($this->input["page"]) 
            or isset($this->input["offset"])
            or isset($this->input["limit"])
            ){
            return true;
        }
    }

    private function paginate($data){
        return Services\PaginatorService::paginate(
            $this->input["limit"] ?? 5,
            $this->input["page"] ?? 1, 
            $this->input["offset"] ?? NULL, 
            $data
        );
    }
}
