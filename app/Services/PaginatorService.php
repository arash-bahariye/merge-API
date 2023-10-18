<?php

namespace App\Services;

class PaginatorService{
    public static function paginate(Int $limit = 5, Int $page = 1,Int $offset = NULL, Array $raw_data){
        if(!isset($offset)){
            $offset = ($page - 1) * $limit;
        }
        $final = array_splice($raw_data, $offset, $limit);
        return $final;
    }
}