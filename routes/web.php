<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return redirect()->route('admin.login');
});
Route::group(['prefix'=>config('admin.prefix'),'as'=>'admin.','namespace'=>'Admin'],function (){
    //login
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login')->name('login');
    Route::post('logout', 'LoginController@logout')->name('logout');
    //home
    Route::get('home', 'HomeController@home')->name('home');
    Route::get('home/flushCache', 'HomeController@flushCache')->name('home.flushCache');
    //user
    Route::resource('user','UserController');
    Route::post('user/batch_destroy','UserController@batch_destroy')->name('user.batch_destroy');
    //role
    Route::resource('role','RoleController');

    Route::get('role/permission/{id}','RoleController@permission')->name('role.permission');
    Route::post('role/doPermission','RoleController@doPermission')->name('role.doPermission');
    //permission
    Route::resource('permission','PermissionController');
    //
    Route::resource('builder','BuilderController');
    Route::get('logs', 'LogsController@index')->name('logs.index');
    //
    Route::resource('task','TaskController');
    Route::post('task/batch_destroy','TaskController@batch_destroy')->name('task.batch_destroy');
    Route::put('task/run/{id}','TaskController@run')->name('task.run');

    //
    Route::get('/',function (){
        return redirect()->route('admin.login');
    });

});

