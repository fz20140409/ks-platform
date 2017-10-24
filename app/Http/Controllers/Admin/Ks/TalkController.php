<?php

namespace App\Http\Controllers\Admin\ks;

use Auth;
use App\Http\Controllers\Admin\BaseController;


/**
 * 对平台说
 * Class TalkController
 * @package App\Http\Controllers\Admin\ks
 */
class TalkController extends BaseController
{

    function index(){

        return view('admin.ks.talk.index');
    }



}
