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

    Route::group(['prefix'=>'ks','as'=>'ks.','namespace'=>'Ks'],function (){
        //网店
        Route::resource('user_info','UserInfoController');
        //优质厂家
        Route::resource('qm','QualityManufacturersController');
        Route::post('qm/batch_destroy','QualityManufacturersController@batch_destroy')->name('qm.batch_destroy');
        //优质商家
        Route::resource('qum','QualityMerchantsController');
        Route::post('qum/batch_destroy','QualityMerchantsController@batch_destroy')->name('qum.batch_destroy');
        //商品管理
        Route::resource('goods','GoodsController');
        //品类设置
        Route::resource('category','CategoryController');
        Route::post('category/batch_destroy','CategoryController@batch_destroy')->name('category.batch_destroy');
        Route::get('category/showSub/{id}','CategoryController@showSub')->name('category.showSub');
        //渠道设置
        Route::resource('salechanel','SalechanelController');
        Route::post('salechanel/batch_destroy','SalechanelController@batch_destroy')->name('salechanel.batch_destroy');
        Route::get('salechanel/showSub/{id}','SalechanelController@showSub')->name('salechanel.showSub');
        //品牌设置
        Route::resource('brand','BrandController');
        Route::post('brand/batch_destroy','BrandController@batch_destroy')->name('brand.batch_destroy');
        //首页图标设置
        Route::resource('menu','MenuController');
        Route::get('menu/updateStatus/{id}','MenuController@updateStatus')->name('menu.updateStatus');

        //轮播管理
        Route::resource('banner','BannerController');

    });


    //
    Route::get('/',function (){
        return redirect()->route('admin.login');
    });

});

