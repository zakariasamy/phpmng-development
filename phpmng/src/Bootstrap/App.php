<?php

namespace Phpmng\Bootstrap; // This namespace defined in composer.json
use Phpmng\Http\Server;
use Phpmng\Http\Request;
use Phpmng\Cookie\Cookie;
use Phpmng\Session\Session;
use Phpmng\Exceptions\Whoops;

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

        //echo Server::get('PHP_SELF');
        
        //echo dirname(Server::get('SCRIPT_NAME'));
        //print_r(Server::path_info('http://phpmng.test/'));

        Request::handle();
        echo Request::get('name');
        print_r(Request::previous());
        
    }

}