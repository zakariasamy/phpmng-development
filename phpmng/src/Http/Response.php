<?php
namespace Phpmng\Http;

class Response{

    /**
     * Output Data
     * 
     * @param mixed $data;
     */

     public static function output($data){
         if(!is_string($data)) // If the data is arrays
            $data = json_encode($data);
        echo $data;
     }

     /**
      * Create json of $data

      * @param mixed $data
      * @return mixed
      */

      public static function json($data){
          return json_encode($data);
      }
}