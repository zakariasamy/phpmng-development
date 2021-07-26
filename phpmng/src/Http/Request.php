<?php

namespace Phpmng\Http;

class Request{

    /*
    * base url like : http://example.com
    *
    */
    private static $base_url;

    /*
    * URL : /ex.php
    *
    */
    private static $url;

    /**
     * Full url : /ex.php?name=ahmed&age=12
     * 
     */
     private static $full_url;

     /**
      * Query string like this part in URL : ?name=ahmed&age=22
      *
      */
    private static $query_string;


     /**
      * Script name
      *
      */
    private static $script_name;

    public static function handle(){
        static::$script_name = Server::get('SCRIPT_NAME');
        static::setBaseURL();
        static::setURL();
    }

    /**
     * Set base URL
     * 
     */
    private static function setBaseURL(){

        $protocol = Server::get('REQUEST_SCHEME') . '://'; # REQUEST_SCHEMA is http or https
        $host = Server::get('HTTP_HOST'); # example.com
        //$script_name = static::$script_name; # /index.php

        static::$base_url = $protocol . $host; #. $script_name;
    }
    /**
     * Get base URL
     * 
     */
    public static function baseURL(){
        return static::$base_url;
    }

    /**
     * Set URL
     * 
     */
    private static function setURL(){
        $requested_url = Server::get('REQUEST_URI'); # /test.php?name=ahmed&age=10
        $query_string = null;

        static::$full_url = $requested_url; 
        // remove query string to make the url /test.php
        if(strpos($requested_url, '?')) # check if there's position for ?
            list($requested_url, $query_string) = explode('?', $requested_url);

        static::$query_string = $query_string;
        static::$url = $requested_url;
    }

    /**
     * Get URL
     * 
     */
    public static function URL(){
        return static::$url;
    }

     /**
     * Get Query string
     * 
     */
    public static function queryString(){
        return static::$query_string;;
    }

     /**
     * Get Full URL
     * 
     */
    public static function fullURL(){
        return static::$full_url;;
    }

    /**
     * Get Request method : like GET & POST
     * 
     * @return string
     */
    public static function method(){
        return Server::get('REQUEST_METHOD');
    }


    /**
     * Get value from get request $key in query string - EX : ?name=ahmed, the value of name is ahmed
     * 
     * @param $key
     * @return string $value
     */
    public static function get($key){
        if(Request::has($key, 'get'))
            return $_GET[$key];
        else
            return null;


    }

    /**
     * Get value from post request
     * 
     * @param $key
     * @return string $value
     */
    public static function post($key){
        if(Request::has($key, 'post'))
            return $_POST[$key];
        else
            return null;
    }

    /**
     * Check on request data that it has $key
     */

     public static function has($key, $type = null){
         if($type == 'post'){
            return isset($_POST[$key]) ? true : false;
         }
         else if($type == 'get'){
            return isset($_GET[$key]) ? true : false;
         }
         else
            return isset($_REQUEST[$key]) ? true : false;

         # Equivilent : using array_key_exists method in one line & use type as array $_POST or $_GET
     }


     /**
      * Get previous URL entered the page from it
      * 
      * @return string
      */
      public static function previous(){
          return Server::get('HTTP_REFERER');
      }

      /**
      * Get All data from Request
      * 
      * @return array
      */
      public static function all(){
        return $_REQUEST;
    }






}