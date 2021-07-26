<?php
namespace Phpmng\View;
use Phpmng\File\File;
use Jenssegers\Blade\Blade;
use Phpmng\Session\Session;

class View{

/**
 * Choose ViewRender (doesn't use blade engine) or bladeRender function
 */

    public static function render($path, $data){
        $errors = Session::flash('errors');  // take the values of erros & remove it from session
        $old = Session::flash('old');
        $data = array_merge($data, ['errors' => $errors, 'old' => $old]);
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