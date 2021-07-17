<?php

namespace Phpmng\Bootstrap; // This namespace defined in composer.json
use Phpmng\Cookie\Cookie;
use Phpmng\Session\Session;
use Phpmng\Exceptions\Whoops;
use Phpmng\Http\Server;

class app{

    public static function run(){

        // Register Whoops to handle erros
        Whoops::handle();

        // start session
         
        Session::start();
        //Session::set('name', 'ziko');
        //Session::set('age', '22');
        //Session::remove('name');
        //echo Session::get('name');
        //echo Session::flash('name');
        //Session::removeAll();
        //print_r(Session::all());

        //Cookie::set('name', 'ziko');
        // Cookie::removeAll();
        // print_r(Cookie::all());

        
        echo '<pre>';
        
        print_r(Server::all()); 
        echo'</pre>';

        echo Server::get('PHP_SELF');
   
        //print_r(Server::path_info('http://phpmng.test/'));
        
    }

}