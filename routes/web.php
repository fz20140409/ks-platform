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
        Route::get('qm/add_qum/{id}','QualityMerchantsController@add_qum')->name('qm.add_qum');//添加优质厂家
        //优质商家
        Route::resource('qum','QualityMerchantsController');
        Route::post('qum/batch_destroy','QualityMerchantsController@batch_destroy')->name('qum.batch_destroy');
        Route::get('qum/add_qum/{id}','QualityMerchantsController@add_qum')->name('qum.add_qum');//添加优质商家
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
        Route::post('salechanel/update_post/{id}','SalechanelController@update_post')->name('salechanel.update_post');
        //品牌设置
        Route::resource('brand','BrandController');
        Route::post('brand/batch_destroy','BrandController@batch_destroy')->name('brand.batch_destroy');
        //首页图标设置
        Route::resource('menu','MenuController');
        Route::get('menu/updateStatus/{id}','MenuController@updateStatus')->name('menu.updateStatus');

        //轮播管理
        Route::resource('banner','BannerController');
        //热搜关键字
        Route::resource('hk','HotKeywordController');
        Route::get('hk/updateStatus/{id}','HotKeywordController@updateStatus')->name('hk.updateStatus');
        Route::post('hk/batch_destroy','HotKeywordController@batch_destroy')->name('hk.batch_destroy');
        //优惠头条管理
        Route::resource('dh','DiscountHeadlinesController');
        //首页热门商品banner
        Route::resource('hgb','HotGoodsBannerController');
        //品类热销榜banner设置
        Route::resource('hcb','HotCategoryBannerController');
        //地区数据字典
        Route::resource('location','LocationController');
        Route::post('location/getData','LocationController@getData')->name('location.getData');

        //上传材料范例
        Route::get('other/material_example','OtherController@material_example')->name('other.material_example');
        Route::post('other/material_example_update','OtherController@material_example_update')->name('other.material_example_update');

        //合作机会
        Route::resource('coop','CooperationOpportunityController');
        Route::get('coop/updateStatus/{id}','CooperationOpportunityController@updateStatus')->name('coop.updateStatus');
        //优化原因设置
        Route::resource('or','OptimizationReasonsController');
        Route::post('or/batch_destroy','OptimizationReasonsController@batch_destroy')->name('or.batch_destroy');
        //合作机会分类
        Route::resource('oc','OpportunityCategoryController');
        Route::post('oc/batch_destroy','OpportunityCategoryController@batch_destroy')->name('oc.batch_destroy');




    });


    //
    Route::get('/',function (){
        return redirect()->route('admin.login');
    });

});

