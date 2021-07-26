<?php
namespace Phpmng\URL;

use Phpmng\Http\Request;

class URL{


    /**
     * Convert url like /users/1 to http://example.com/users/1
     * 
     * @param string $path
     * @return string $path
     */

     public static function path($path){
         $path = trim($path, '/');
         return trim(Request::baseUrl(), '/') . '/' . $path;
     }

     public static function previous(){
         return Request::previous();
     }

     /**
      * redirect to $path
      *
      * @param string $path
      */
     public static function redirect($path){
        header("Location: " . $path);
        die();
     }
}