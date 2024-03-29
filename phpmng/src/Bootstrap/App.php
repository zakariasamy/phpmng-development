<?php

namespace Phpmng\Bootstrap; // This namespace defined in composer.json
use Phpmng\URL\URL;
use Phpmng\File\File;
use Phpmng\Http\Server;
use Phpmng\Http\Request;
use Phpmng\Router\Route;
use Phpmng\Cookie\Cookie;
use Phpmng\Http\Response;
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


        // echo '<pre>';
        
        // print_r(Server::all()); 
        // echo'</pre>';

        //echo Server::get('PHP_SELF');
        
        //echo dirname(Server::get('SCRIPT_NAME'));
        //print_r(Server::path_info('http://phpmng.test/'));

        # Handle the requests
        Request::handle();
        //echo Request::get('name');
        //print_r(Request::previous());


        // echo File::path('routes/web.php');
        // echo File::exists('routes/web.php');
        // print_r(File::require_directory('routes'));
        //File::require_file('routes/web.php');

        # Require all route files in route directory
        File::require_directory('routes');

        // echo '<pre>';
        // print_r(Route::allRoutes());
        // echo '</pre>';

        // Handle the routes
        $data = Route::handle();
        
        //print_r( Route::invoke(['middleware'=>'admin'],[]));
        //echo Route::executeMiddleware(['middleware' => 'admin']);
        
        # Show the data returned by Route
        Response::output($data);

        //redirect(URL::path('home'));
        
    }

}