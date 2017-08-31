<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminPermissionAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $action = Route::currentRouteName();

        if(!Auth::user()->can($action)){
            //上一个请求
            $data['url']=$_SERVER['HTTP_REFERER'];
            $data['jump_time']=3;
            $data['info']='无权访问';
            return response()->view('errors.nop',compact('data'));
            //return redirect('/');

        }
        return $next($request);
    }
}
