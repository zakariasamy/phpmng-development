
<?php

/*
* phpmng Framework
*/

/**
 * phpmng - A PHP Framework
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

/*
|---------------------------------------------------
| Register the autoloader
|---------------------------------------------------
|
| Load the autoloader that have generated class that will be used
*/
require __DIR__.'/../vendor/autoload.php';


/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

require_once __DIR__.'/../bootstrap/app.php';


/*
|---------------------------------------------------
| Run the application
|---------------------------------------------------
|
| Handle the request and send response
*/
Application::run();


?>