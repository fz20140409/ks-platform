<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * 后台登录控制器
 * 使用laravel 用户认证脚手架
 * Class LoginController
 * @package App\Http\Controllers\Admin
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/admin/home';

    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    //重写
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.home');
        }

        return view('admin.login.login');
    }
    //登录成功，记录ip,登录时间
    protected function attemptLogin(Request $request)
    {
        if ($this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        )
        ) {
            $user = Auth::user();
            $user->ip = ip2long($request->getClientIp());
            $user->last_time = date('Y-m-d H:i:s', time());
            $user->save();
            return true;
        }
        return false;
    }
}
