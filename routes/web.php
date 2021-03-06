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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
        Route::get('qm/add_qum/{id}','QualityManufacturersController@add_qum')->name('qm.add_qum');//添加优质厂家
        Route::post('qm/batch_add','QualityManufacturersController@batch_add')->name('qm.batch_add');//批量添加优质厂家
        //优质商家
        Route::resource('qum','QualityMerchantsController');
        Route::post('qum/batch_destroy','QualityMerchantsController@batch_destroy')->name('qum.batch_destroy');
        Route::get('qum/add_qum/{id}','QualityMerchantsController@add_qum')->name('qum.add_qum');//添加优质商家
        Route::post('qum/batch_add','QualityMerchantsController@batch_add')->name('qum.batch_add');//批量添加优质厂家
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
        //与我有关-更多图标设置
        Route::resource('micon','MoreIconController');
        Route::get('micon/updateStatus/{id}','MoreIconController@updateStatus')->name('micon.updateStatus');


        //轮播管理
        Route::resource('banner','BannerController');
        //热搜关键字
        Route::resource('hk','HotKeywordController');
        Route::get('hk/updateStatus/{id}','HotKeywordController@updateStatus')->name('hk.updateStatus');
        Route::post('hk/batch_destroy','HotKeywordController@batch_destroy')->name('hk.batch_destroy');
        //优惠头条管理
        Route::resource('dh','DiscountHeadlinesController');
        Route::get('dh/updateStatus/{id}','DiscountHeadlinesController@updateStatus')->name('dh.updateStatus');
        Route::get('dh/getOptimize/{id}','DiscountHeadlinesController@getOptimize')->name('dh.getOptimize');
        //优惠头条分类
        Route::resource('dhc','DiscountHeadlinesCategoryController');
        Route::post('dhc/batch_destroy','DiscountHeadlinesCategoryController@batch_destroy')->name('dhc.batch_destroy');
        //优惠头条优化原因
        Route::resource('dhor','DiscountHeadlinesOptimizationReasonsController');
        Route::post('dhor/batch_destroy','DiscountHeadlinesOptimizationReasonsController@batch_destroy')->name('dhor.batch_destroy');
        //优惠商品管理
        Route::resource('dg','DiscountGoodsController');
        Route::post('dg/batch_destroy','DiscountGoodsController@batch_destroy')->name('dg.batch_destroy');
        Route::post('dg/batch_add','DiscountGoodsController@batch_add')->name('dg.batch_add');


        //首页热门商品banner
        Route::resource('hgb','HotGoodsBannerController');
        //品类热销榜banner设置
        Route::resource('hcb','HotCategoryBannerController');
        //地区数据字典
        Route::resource('location','LocationController');
        Route::post('location/getData','LocationController@getData')->name('location.getData');
        //背景及icon设置
        Route::resource('mb','MerchantBackgroundController');

        //上传材料范例
        Route::get('other/material_example','OtherController@material_example')->name('other.material_example');
        Route::post('other/material_example_update','OtherController@material_example_update')->name('other.material_example_update');
        //模块设置
        Route::get('other/module_settings','OtherController@module_settings')->name('other.module_settings');
        Route::post('other/do_module_settings','OtherController@do_module_settings')->name('other.do_module_settings');
        //用户中心
        Route::get('other/user_center','OtherController@user_center')->name('other.user_center');
        Route::post('other/do_user_center','OtherController@do_user_center')->name('other.do_user_center');
        //客服设置
        Route::get('other/kefu_setting','OtherController@kefu_setting')->name('other.kefu_setting');
        Route::post('other/kefu_setting_update','OtherController@kefu_setting_update')->name('other.kefu_setting_update');
        //客商平台服务协议
        Route::get('other/service_contract','OtherController@service_contract')->name('other.service_contract');
        Route::post('other/service_contract_update','OtherController@service_contract_update')->name('other.service_contract_update');
        //隐私声明
        Route::get('other/privacy_policy','OtherController@privacy_policy')->name('other.privacy_policy');
        Route::post('other/privacy_policy_update','OtherController@privacy_policy_update')->name('other.privacy_policy_update');

        //合作机会
        Route::resource('coop','CooperationOpportunityController');
        Route::get('coop/updateStatus/{id}','CooperationOpportunityController@updateStatus')->name('coop.updateStatus');
        Route::get('coop/getOptimize/{id}','CooperationOpportunityController@getOptimize')->name('coop.getOptimize');
        //优化原因设置
        Route::resource('or','OptimizationReasonsController');
        Route::post('or/batch_destroy','OptimizationReasonsController@batch_destroy')->name('or.batch_destroy');
        //合作机会分类
        Route::resource('oc','OpportunityCategoryController');
        Route::post('oc/batch_destroy','OpportunityCategoryController@batch_destroy')->name('oc.batch_destroy');

        //系统消息
        Route::resource('sysm','SysMessageController');

        //平台客服电话设置
        Route::resource('mk', 'MerchantKfController');
        Route::post('mk/batch_destroy','MerchantKfController@batch_destroy')->name('mk.batch_destroy');
        Route::post('mk/update_post/{id}','MerchantKfController@update_post')->name('mk.update_post');
        //分类图标设置
        Route::resource('ci','ClassifyIconController');
        Route::post('ci/batch_destroy','ClassifyIconController@batch_destroy')->name('ci.batch_destroy');
        Route::get('ci/showSub/{id}','ClassifyIconController@showSub')->name('ci.showSub');
        Route::get('ci/updateStatus/{id}','ClassifyIconController@updateStatus')->name('ci.updateStatus');

        //用户管理
        Route::resource('um','UserManageController');
        Route::get('um/getPersonInfo/{id}','UserManageController@getPersonInfo')->name('um.getPersonInfo');
        Route::get('um/getBossInfo/{id}','UserManageController@getBossInfo')->name('um.getBossInfo');
        Route::get('um/getBusinessInfo/{id}','UserManageController@getBusinessInfo')->name('um.getBusinessInfo');
        Route::get('um/getTransactorInfo/{id}','UserManageController@getTransactorInfo')->name('um.getTransactorInfo');
        Route::get('um/getCompanyInfo/{id}','UserManageController@getCompanyInfo')->name('um.getCompanyInfo');

        //app版本管理
        Route::resource('av','AppVersionController');
        Route::post('av/batch_destroy','AppVersionController@batch_destroy')->name('av.batch_destroy');
        Route::post('av/update_post/{id}','AppVersionController@update_post')->name('av.update_post');
        //对平台说
        Route::get('talk/index','TalkController@index')->name('talk.index');
        //对平台说接口

        //有话说角色设置
        Route::resource('tr','TalkRoleController');


    });


    //
    Route::get('/',function (){
        return redirect()->route('admin.login');
    });


});

//获取用户信息接口
Route::get('talk/getUserDetailInfo',function (Request $request) {
    $uid=$request->uid;
    if(empty($uid)||empty(Auth::id())){
        return response()->json(array());
    }
    $url=config('admin.api_url').'/index/getUserDetailInfo';
    $data=[
        'param'=>['uid'=>$uid]
    ];
    return $result=curl_request($url,true,$data);

});
//获取account和token接口
Route::get('talk/getLoginInfo',function (Request $request) {
    $uid=Auth::id();
    if(empty($uid)){
        return response()->json(array());
    }
    $token=DB::table('merchant_role_rela')->select('token','peerid')->where('uid',$uid)->first();

    if(empty($token)){
        return response()->json(array());
    }

    return response()->json(array('account'=>$token->peerid,'token'=>$token->token));


});

//用户主营渠道信息获取
Route::get('talk/getUserSalechanel',function (Request $request) {
    $uid=$request->uid;
    if(empty($uid)||empty(Auth::id())){
        return response()->json(array());
    }
    $url=config('admin.api_url').'/index/getUserSalechanel';
    $data=[
        'param'=>['uid'=>$uid]
    ];
    return $result=curl_request($url,true,$data);

});
//用户主营品类信息获取
Route::get('talk/getUserCategory',function (Request $request) {
    $uid=$request->uid;
    if(empty($uid)||empty(Auth::id())){
        return response()->json(array());
    }
    $url=config('admin.api_url').'/index/getUserCategory';
    $data=[
        'param'=>['uid'=>$uid]
    ];
    return $result=curl_request($url,true,$data);

});
//用户经销品牌信息获取
Route::get('talk/getUserBrandInfo',function (Request $request) {
    $uid=$request->uid;
    if(empty($uid)||empty(Auth::id())){
        return response()->json(array());
    }
    $url=config('admin.api_url').'/index/getUserBrandInfo';
    $data=[
        'param'=>['uid'=>$uid]
    ];
    return $result=curl_request($url,true,$data);

});
//用户业务辐射区信息获取
Route::get('talk/getUserYwfs',function (Request $request) {
    $uid=$request->uid;
    if(empty($uid)||empty(Auth::id())){
        return response()->json(array());
    }
    $url=config('admin.api_url').'/index/getUserYwfs';
    $data=[
        'param'=>['uid'=>$uid]
    ];
    return $result=curl_request($url,true,$data);

});
//用户主营产品信息获取
Route::get('talk/getUserMaingoods',function (Request $request) {
    $uid=$request->uid;
    if(empty($uid)||empty(Auth::id())){
        return response()->json(array());
    }
    $url=config('admin.api_url').'/index/getUserMaingoods';
    $data=[
        'param'=>['uid'=>$uid]
    ];
    return $result=curl_request($url,true,$data);

});

