<?php
namespace Phpmng\View;
use Jenssegers\Blade\Blade;
use Phpmng\File\File;

class View{

/**
 * Choose ViewRender (doesn't use blade engine) or bladeRender function
 */

    public static function render($path, $data){
        return static::bladeRender($path, $data);
    }
    /**
     * Render View file
     * EX in controller: View::render('dashboard.user', ['name' => 'ahmed', 'age' => 24])
     * 
     * @param string $path
     * @param array $data
     */

     public static function viewRender($path, $data){
        $path = 'views' .  File::DS() . str_replace('.', File::DS(), $path) . '.php';

        if(File::exists($path)){
            extract($data);
            $path = File::path($path); // Get full path
            include $path;
            return '';

        }
        else
            throw new \Exception('the view file '. $path . ' doesn\'t exist');  
     }

     /** 
      * Render the view files using blade engine
      * 
      * @param string $path
      * @param array $data
      */
      public static function bladeRender($path, $data){

        $blade = new Blade(File::path('views'), File::path('storage/cache'));

        return $blade->make($path, $data)->render();

      }
}