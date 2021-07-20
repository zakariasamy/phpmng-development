<?php
namespace App\Controllers;

use Phpmng\URL\URL;
use Phpmng\View\View;


class HomeController{
    public function index(){
        //return "Hello from index function";
        //return URL::previous();
        //return URL::redirect(URL::previous());
        //return ['ahmed' => '32', 'mohamed' => '25'];

        return View::render('admin.dashboard', ['name' => 'ahmed', 'age' => 24]);
    }


}