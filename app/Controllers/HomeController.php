<?php
namespace App\Controllers;

use Phpmng\URL\URL;
use App\Models\User;
use Phpmng\View\View;
use Phpmng\Database\Database;


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
        //return $users = Database::table('users')->paginate(2);
        //return View::render('admin.dashboard', ['users' => $users]);

        // Test User Model
        $users = User::paginate(2);

        // Test Helper function
        return view('admin.dashboard', ['users' => $users]);
    }


}