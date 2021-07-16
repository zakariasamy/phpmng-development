<?php

namespace Phpmng\Bootstrap; // This namespace defined in composer.json
use Phpmng\Exceptions\Whoops;

class app{

    public static function run(){

        echo "From Bootstrap/App";
        Whoops::handle();
        
        throw new \Exception("some errors");
        
    }

}