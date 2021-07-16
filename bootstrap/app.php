<?php 
// The application will start from here

use Phpmng\Bootstrap\App;


class Application{


    public static function run(){

        /*
        * 
        * Define Root path
        *
        */
        define('ROOT', realpath(__DIR__ . '/..'));

        /**
         * Directory separator
         *
         */
        define('DS', DIRECTORY_SEPARATOR); // In windows it will be \ but in linux will be /


        App::run();
    }
}
?>
