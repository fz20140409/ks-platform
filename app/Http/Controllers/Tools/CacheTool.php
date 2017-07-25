<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Tools\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CacheTool
{
    static function cacheMneu()
    {
        $key='user_menu_'.Auth::id();

        if(!Cache::tags('user_menu')->has($key)){
            $perms = array();
            $datas = Auth::user()->roles()->with(['perms' => function ($query) {
                $query->where('ishow', 1);
            }])->get()->toArray();
            foreach ($datas as $data) {
                $perms = array_merge_recursive($perms, $data['perms']);
            }
            $layer = Category::toLayer($perms);
            $perms = Category::proMenu($layer);
            Cache::tags('user_menu')->forever($key, $perms);
        }


    }

    static function flush(){
        Cache::tags('user_menu')->flush();
        self::cacheMneu();
        Cache::tags(Config::get('entrust.role_user_table'))->flush();
        Cache::tags(Config::get('entrust.permission_role_table'))->flush();
    }


}
