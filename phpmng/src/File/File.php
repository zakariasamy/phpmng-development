<?php
namespace Phpmng\File;

class File{

    private static $full_path;
    /**
     * Get the root path which defined in bootstrap/app.php
     * 
     * @return string
     */
    public static function root(){
        return ROOT;
    }

    /**
     * Get The Directory separator
     * 
     * @return string
     */

    public static function DS(){
        return DS;
    }

    /**
     * Get full path of file path
     * 
     * @param string $file
     * 
     * @return string path
     */

     public static function path($path){ # ex : $path = routes/web.php
         
        $path = File::root() . File::DS() . $path;
        $path = str_replace(['/', '\\'], File::DS(), $path);
        return $path;
     }

     /**
      * Check that file exists
      * 
      * @param string $path
      * 
      * @return bool
      */
     public static function exists($path){
         static::$full_path = static::path($path);
         return file_exists(static::$full_path);
     }

     /**
      * Require file
      *
      * @param string $path
      * @return mixed
      */
      public static function require_file($path){
          if(static::exists($path))
            return require_once static::$full_path;
      }

      /**
       * Require all files in Directory path
       * 
       * @param string $path
       */
      public static function require_directory($path){ # $path like routes or any main path like app or config
          $files = array_diff(scandir(File::path($path)), ['.', '..']);
        foreach ($files as $index => $file){
            $file_path = $path . File::DS() . $file;
            $full_path = File::path($file_path);
            require_once $full_path;
        }

      }
}