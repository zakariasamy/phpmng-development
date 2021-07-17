<?php
namespace Phpmng\Session;

class Session{


    public static function start(){

        // if there's no session
        if(! session_id()){

            /*
            * modify settings in php.ini to make the session more secure
            * cookies will be used as the mandatory storage to preserve session id. It prevents session hijacking.
            */
            ini_set('session.use_only_cookies', 1);

            // Start session
            session_start();
        }
    }

    /*
    * Set new session
    *
    * @param string $key
    * @param string $value
    *
    * @return string $value;
    */

    public static function set($key, $value){

        $_SESSION[$key] = $value;
        return $value;

    }

    /*
    * Check That session has $key
    *
    * @param string $key
    * 
    * @return boolean
    */
    public static function has($key){

        return isset($_SESSION[$key]);
    }

    /*
    * Get session by $key
    *
    * @param string $key
    * 
    * @return mixed (string if session exists & null if no session)
    */
    public static function get($key){

        return static::has($key) ? $_SESSION[$key] : null;
    }

    /*
    * Remove session by $key
    *
    * @param string $key
    * 
    * @return mixed (true if session exists & null if no session)
    */

    public static function remove($key){
 
        if(static::has($key)){
            unset($_SESSION[$key]);
            return 1;
        }
        else
            return null;

    }

    /*
    * Return All session
    *
    * @return array
    *
    */
    public static function all(){
        return $_SESSION;
    }

    /*
    * Remove all sessions
    *
    *
    */

    public static function removeAll(){

        foreach(static::all() as $key => $value){
            static::remove($key);
        }
    }

    /*
    * Get flash session
    *
    * @param string $key
    * @return string $value
    *
    */

    public static function flash($key){
        $value= null;
        
        if(static::has($key)){
            $value = static::get($key);
            unset($_SESSION[$key]);
        }
        return $value;
    }

}