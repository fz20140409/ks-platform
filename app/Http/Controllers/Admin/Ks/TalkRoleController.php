<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\UploadTool;


/**
 * 有话说角色设置
 * Class TalkRoleController
 * @package App\Http\Controllers\Admin\Ks
 */
class TalkRoleController extends BaseController
{

    public function index(Request $request)
    {
        //条件
        $infos = DB::table('merchant_role_rela')->paginate(10);

        return view('admin.ks.tr.index', ['infos' => $infos]);

    }


    public function create(Request $request)
    {
        $users=DB::table('lara_users')->select('email','id')->get();


        return view('admin.ks.tr.create',compact('users'));
    }


    public function store(Request $request)
    {


        $name = $request->name;//角色名称
        $uid = $request->uid;//绑定的帐号
        if(empty($name)){
            return redirect()->back()->with('tip', '角色名称不能为空');

        }
        if($uid==-1){
            return redirect()->back()->with('tip', '请选择帐号');
        }

        //图片
        if ($request->hasFile('icon')) {
            $icon=UploadTool::UploadImgForm($request,'icon');
            if (isset($icon['error'])){
                return redirect()->back()->withInput()->with('upload', $icon['error']);
            }else{
                $icon=$icon['url'];
            }
        }else{
            return redirect()->back()->withInput()->with('upload', '请上传图标');
        }

        //帐号
        $account='im'.uniqid();
        $url="https://api.netease.im/nimserver/user/create.action";
        $data=['accid'=>$account,'name'=>$name,'icon'=>$icon];
        $header=imHeader();

        $result=curl_request($url,true,$data,$header);
        $result=json_decode($result,true);
        if($result['code']!=200){
            return redirect()->back()->with('tip', '添加失败(导入网易云帐号失败)');
        }

        $insert = [
            'mid'=>999999,
            'role_name'=>$name,
            'peerid'=>$account,
            'role_icon'=>$icon,
            'enabled'=>1,
            'token'=>$result['info']['token'],
            'name'=>$result['info']['name'],

        ];
        if (DB::table('merchant_role_rela')->insert($insert)) {
            return redirect()->back()->with('tip', '添加成功');
        }

    }


    public function show($id)
    {

    }


    public function edit($id, Request $request)
    {

    }


    public function update(Request $request, $id)
    {

    }


    public function destroy(Request $request, $id)
    {

    }






}
