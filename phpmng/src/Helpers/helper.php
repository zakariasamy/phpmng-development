<?php

use Phpmng\View\View;



if(! function_exists('view') ){
    /**
     * Render the view
     * 
     * @param string $path
     * @param array data
     * 
     * @return mixed
     */

    function view($path, $data = []){
        return View::render($path,$data);
    }
}

/**
 * Request get
 *
 * @param string $key
 * @return mixed
 */
if (! function_exists('request')) {
    function request($key) {
        return Phpmng\Http\Request::value($key);
    }
}

/**
 * Redirect
 *
 * @param string $path
 * @return mixed
 */
if (! function_exists('redirect')) {
    function redirect($path) {
        return Phpmng\URL\URL::redirect($path);
    }
}

/**
 * Previous
 *
 * @return mixed
 */
if (! function_exists('previous')) {
    function previous() {
        return Phpmng\URL\URL::previous();
    }
}

/**
 * Asset path
 *
 * @param string $path : Get full path of url
 * @return mixed
 */
if (! function_exists('asset')) {
    function asset($path) {
        return Phpmng\URL\URL::path($path);
    }
}

/**
 * Dump and die
 *
 * @param string $data
 * @return void
 */
if (! function_exists('dd')) {
    function dd($data) {
        echo "<pre>";
        if (is_string($data)) {
            echo $data;
        } else {
            print_r($data);
        }
        echo "</pre>";
        die();
    }
}

/**
 * Get session data
 *
 * @param string $key
 * @return string $data
 */
if (! function_exists('session')) {
    function session($key) {
        return Phpmng\Session\Session::get($key);
    }
}

/**
 * Get session flash data
 *
 * @param string $key
 * @return string $data
 */
if (! function_exists('flash')) {
    function flash($key) {
        return Phpmng\Session\Session::flash($key);
    }
}

/**
 * Show pagination links
 *
 * @param string $current_page
 * @param string $pages
 * @return string
 */
if (! function_exists('links')) {
    function links($current_page, $pages) {
        return Phpmng\Database\Database::links($current_page, $pages);
    }
}
