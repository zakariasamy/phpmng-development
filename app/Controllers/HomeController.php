<?php
namespace App\Controllers;

use Phpmng\Database\Database;
use Phpmng\URL\URL;
use Phpmng\View\View;


class HomeController{
    public function index(){
        //return "Hello from index function";
        //return URL::previous();
        //return URL::redirect(URL::previous());
        //return ['ahmed' => '32', 'mohamed' => '25'];
        
        //return View::render('admin.dashboard', ['name' => 'ahmed', 'age' => 24]);
        //return Database::table('users')->insert(['name' => 'yasmeen']);
        //return Database::table('users')->where('id', '=', '8')->update(['name' => 'mohamed']);
        //return Database::table('users')->select('name')->where('id', '=', 1)->get();
        //return Database::table('users')->select('name')->paginate(2);
        $users = Database::table('users')->paginate(2);
        return View::render('admin.dashboard', ['users' => $users]);

    }


}