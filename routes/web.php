<?php

use Phpmng\Router\Route;

//Route::get('/user/{id}/edit', 'HomeController@index');
Route::get('/admin/dashboard', 'HomeController@index');

Route::get('/try', function(){
    return '<a href="/user/1/edit">click</a>';
});
Route::any('/home', 'HomeController@index');

Route::prefix('admin', function(){
    Route::middleware('Admin|Owner', function(){
        
        //Route::get('category/{id}', 'CategoryController@index');
        //Route::post('category', 'CategoryController@index');
    

        //Route::get('category', 'CategoryController@index');

    });


    
});