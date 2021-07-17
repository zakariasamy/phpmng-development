<?php
namespace Phpmng\Http;


class Server{

    /*
    *
    * Server Constructor
    *
    */

    private function __construct() {}


    /*
    *
    * Check if server has $key
    *
    * @return bool
    */

    public static function has($key){

        return isset($_SERVER[$key]);

    }


    /*
    *
    * Get All Server Data
    *
    * @return array
    */

    public static function all(){
        return $_SERVER;
    }

    /*
    * Get value by $key
    *
    * @param string $key
    * @return mixed (string $value or null)
    */

    public static function get($key){

        return static::has($key) ? $_SERVER[$key] : null;

    }

    /*
    * Get info about $path
    *
    * @param string $path
    *
    * @return array
    */

    public static function path_info($path){

        return pathinfo($path);

    }


}