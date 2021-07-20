<?php
namespace Phpmng\Cookie;

class Cookie{


    /*
    * Set new Cookie
    *
    * @param string $key
    * @param string $value
    *
    * @return string $value;
    */

    public static function set($key, $value){

        $expires = time() + (60 * 60 * 24 * 365); // expires after 1 Year
        setcookie($key, $value, $expires, '/', '', false, true);
        return $value;

    }

    /*
    * Check That Cookie has $key
    *
    * @param string $key
    * 
    * @return boolean
    */
    public static function has($key){

        return isset($_COOKIE[$key]);
    }

    /*
    * Get Cookie by $key
    *
    * @param string $key
    * 
    * @return mixed
    */
    public static function get($key){

        return static::has($key) ? $_COOKIE[$key] : null;
    }

    /*
    * Remove Cookie by $key
    *
    * @param string $key
    * 
    * @return mixed
    */

    public static function remove($key){
 
        if(static::has($key)){
            setcookie($key, null, -1);
            return 1;
        }
        else
            return null;

    }

    /*
    * Return All Cookies
    *
    * @return array
    *
    */
    public static function all(){
        return $_COOKIE;
    }

    /*
    * Remove all Cookies
    *
    *
    */

    public static function removeAll(){

        foreach(static::all() as $key => $value){
            static::remove($key);
        }
    }


}