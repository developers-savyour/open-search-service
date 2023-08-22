<?php

namespace Sav\OpenSearch;

class OpenSearch
{

    public function __construct()
    {
        //
    }

    public static function count(){
        return rand(1,9999);
    }

    public function count_token($text){
        return count(self::gpt_encode($text));
    }

}
