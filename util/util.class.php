<?php
namespace util;

class util {
    static function redirect($url){

        if($url==baseUrl)
        header("Location:".baseUrl);
        else
        header("Location:".baseUrl.$url);
        exit;
    }

}