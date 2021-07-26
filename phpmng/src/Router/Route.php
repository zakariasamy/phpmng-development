<?php

namespace Phpmng\Router;

use Phpmng\View\View;
use Phpmng\Http\Request;

class Route{

    /**
     * Route Container : contain all routes that developer will write
     */

    private static $routes = [];

     /**
      * Middleware
      */
    private static $middleware;

     /**
      * Prefix
      */
    private static $prefix;

      /**
       * Add route to static:$routes array
       * @param string $methods
       * @param string $url
       * @param object (callback $callback)
       */
    private static function add($methods, $url, $callback){
        $url = static::$prefix . '/' . trim($url, '/'); # we trim / in left because we put it after prefix
        # and remove / from right if exists : /dashboard/cat/ -> /dashboard/cat

        $url = $url ? $url : '/'; # put / if the url empty & with no prefix

        # in methods, it may be get or post or get|post
        foreach (explode('|', $methods) as $method) { 
            static::$routes[] = [
                'url' => $url,
                'method' => $method,
                'callback' => $callback,
                'middleware' => static::$middleware
            ];            
        }
    }

       /**
       * Handle route with GET method
       * @param string $url
       * @param object (callback $callback)
       */

      public static function get($url, $callback){
           static::add('GET', $url, $callback);
       }

       /**
       * Handle route with POST method
       * @param string $url
       * @param object (callback $callback)
       */

       public static function post($url, $callback){
           static::add('POST', $url, $callback);
       }


       /**
       * Handle route with Any method (GET & POST will work)
       * @param string $url
       * @param object (callback $callback)
       */

       public static function any($url, $callback){
           static::add('GET|POST', $url, $callback);
       }

       /**
        * return all routes for test purposes
        */

       public static function allRoutes(){
           return static::$routes;
       }



    /**
     * Set prefix for routes
     * 
     * EX: 
     * Route::prefix('dashboard', function(){
     *  Route::get('category', 'CategoryController@index');
     * 
     *      Route::prefix('users', function(){
     * 
     *      })
     * 
     *  });
     * @param string $prefix
     * @param string $callback
     */

    public static function prefix($prefix, $callback){
        # save the old prefix so if we have prefix inside prefix's function we can go back
        $parent_prefix = static::$prefix; 
        static::$prefix .= '/' . trim($prefix, '/');

        if(is_callable($callback)) # Check if it's truly callback function
            call_user_func($callback); # Call it
        else
            throw new \Exception('Please provide valid callback function');

        static::$prefix .= $parent_prefix;
    }

    /**
     * Set Middleware for routes
     * 
     * EX: 
     * Route::middleware('admin|owner', function(){
     *  Route::get('category', 'CategoryController@index');
     * 
     *      Route::middleware('auth', function(){
     * 
     *      })
     * 
     *  });
     * @param string $middleware
     * @param string $callback
     */

    public static function middleware($middleware, $callback){
        # save the old middleware so if we have middleware inside middleware's function we can go back
        $parent_middleware = static::$middleware; 
        static::$middleware .= '|' . trim($middleware, '|');

        if(is_callable($callback)) # Check if it's truly callback function
            call_user_func($callback); # Call it
        else
            throw new \Exception('Please provide valid callback function');

        static::$middleware .= $parent_middleware;
    }

    /**
     * Get Url from user & check if it match any route
     * 
     * @return mixed
     */
    public static function handle(){
        $url = Request::url(); // suppose we open /user/1/edit

        # Check on every route that it's lie what user typed
        foreach(static::$routes as $route){

            # convert /user/{id}/edit to /user/([a-zA-Z0-9]+)/edit
            // note that we used # as delimiter in php at end & beginning or other special chars as rules of php
            $pattern = preg_replace('#{(.*?)}#', '([a-zA-Z0-9]+)', $route['url']);
            
            if(preg_match('#' . $pattern . '#', $url, $matches)){ 
                // Now we made sure ther's match in url this route
                // special case : /user/1/edit/anything will be accepted !
                // Solution : count the number of / in url and route
                if(substr_count($url, '/') != substr_count($route['url'], '/'))
                    continue;

                /**another case : if we write existed url like: admi/dash as admin/dasha123 it will be accepted
                *  Solution : check if last word in URL (dash123) = last word in route (dash) in case
                *  the last word is not variable like {id}
                */
                if($route['url'][-1] != '}'){ // Not Variable like in /user/{id}
                    $developer_last_word_position = strrpos($route['url'], '/') + 1;
                    $browser_last_word_position = strrpos($url, '/') + 1;

                    $developer_last_word = substr($route['url'], $developer_last_word_position);
                    $browser_last_word = substr($url, $browser_last_word_position);
                    if($developer_last_word != $browser_last_word)
                        continue;
            }

                
                if($route['method'] != Request::method())
                    continue; // There's no match

                array_shift($matches); # remove first item because it show /user/1/edit;

                $params = $matches; // params like 1 in /user/1/edit

                return Route::invoke($route, $params);
                
            }
            
        } // End foreach

        return View::render('errors.404', []);
    }

    /**
     * invoke route because it match the url written by user
     * 
     * @param array $route
     * @param array $params
     */

    public static function invoke($route, $params){
        # Execute middleware so it can prevent continue executing this route
        static::executeMiddleware($route);

        # Execute the callback function in this style : function(){} Or in this : Homecontroller@index
        $callback = $route['callback'];

        if(is_callable($callback))
            return call_user_func_array($callback, $params); #execute it

        elseif(strpos($callback, '@')){ # in this style : HomeController@index
            list($controller, $method) = explode('@', $callback);
            $controller = 'App\Controllers\\' . $controller;
            if(class_exists($controller)){ # find it via namespace & class name
                $object = new $controller;
                if(method_exists($object, $method))
                    return call_user_func_array([$object, $method], $params);
                else
                    throw new \BadFunctionCallException("The method " . $method . "don't exists at" . $controller . " Controller");
            }
            else
                throw new \ReflectionException("class " . $controller . " is not found");
            
            
        }
        else
            throw new \InvalidArgumentException("Plaese provide valid callback function");

    }


    /**
     * Execute Middleware
     * 
     * @param array $route
     */
    public static function executeMiddleware($route){
        foreach(explode('|',$route['middleware']) as $middleware){
            if($middleware != ''){
                $middleware = 'App\Middleware\\' . $middleware;

                if(class_exists($middleware)){
                    $object = new $middleware;
                    return call_user_func_array([$object, 'handle'], []); // call handle function
                }
                else
                    throw new \ReflectionException("class " . $middleware . " is not found");
            }
        }
    }
}