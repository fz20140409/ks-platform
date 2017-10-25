<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('talk/getUserDetailInfo',function (Request $request) {
    $uid=$request->uid;
    if(empty($uid)){
        return response()->json(array());
    }
    $url=config('admin.api_url').'/index/getUserDetailInfo';
    $data=[
        'param'=>['uid'=>$uid]
    ];
    return $result=curl_request($url,true,$data);

});
