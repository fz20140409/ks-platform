<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\CacheTool;

/**
 * 后台主页控制器
 * Class HomeController
 * @package App\Http\Controllers\Admin
 */
class HomeController extends BaseController
{

    function home()
    {
        CacheTool::cacheMneu();
        return view('admin.home.home');
    }

    public function flushCache()
    {
        CacheTool::flush();
        return response()->json(['status' => 1]);
    }

}
