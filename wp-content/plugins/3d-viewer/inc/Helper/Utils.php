<?php
namespace BP3D\Helper;

class Utils {

    public static function isset($array, $key, $default = false){
        if(isset($array[$key])){
            return $array[$key];
        }
        return $default;
    }

    public static function isset2($array, $key1, $key2, $default = false){
        if(isset($array[$key1][$key2])){
            return $array[$key1][$key2];
        }
        return $default;
    }
}