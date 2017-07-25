<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 后台抽象控制器
 * Class BaseController
 * @package App\Http\Controllers\Admin
 */
class BaseController extends Controller
{


    protected $page_sizes=array();//定义每页显示几条数组
    protected $page_size=10;////定义每页显示几条

    function __construct(Request $request)
    {
        $this->init($request);


    }

    protected function init(Request $request){
        $this->initMiddleware();
        $this->initFields($request);

    }

    protected function initMiddleware(){
        $this->middleware('admin_login_auth');
        $this->middleware('admin_permission_auth');
    }
    protected function initFields(Request $request){

        $this->page_sizes = ['10' => 10, '25' => 25, '50' => 50, '100' => 100];
        if(isset($request->page_size) ){
            $this->page_size = $request->page_size;
        }
    }


}
