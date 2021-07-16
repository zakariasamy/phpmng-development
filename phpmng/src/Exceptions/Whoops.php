<?php

namespace Phpmng\Exceptions;

class Whoops{
    
    public static function handle(){
        // Register whoops library to handle Errors

        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }


}
