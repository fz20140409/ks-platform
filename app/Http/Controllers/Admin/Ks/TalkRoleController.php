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
        $infos = DB::table('merchant_role_rela as a')->select('a.*','b.email as email','b.name as nkname')->leftJoin('lara_users as b','a.uid','=','b.id')->paginate(10);

        return view('admin.ks.tr.index', ['infos' => $infos]);

    }


    public function create(Request $request)
    {

        $sql="select email,id from lara_users where id  not in (select uid from merchant_role_rela)";
        $users=DB::select($sql);
        return view('admin.ks.tr.create',compact('users'));
    }


    public function store(Request $request)
    {


        $name = $request->name;//角色名称
        $uid = $request->uid;//绑定的帐号
        if(empty($name)){
            return redirect()->back()->with('tip', '角色名称不能为空');

        }
        if(empty($uid)){
            return redirect()->back()->with('tip', '请先创建需要绑定的帐号');
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
            'uid'=>$uid,

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
        $info = DB::table('merchant_role_rela')->where('id', $id)->first();
        $uid=$info->uid;
        $sql="select email,id from lara_users where id  not in (select uid from merchant_role_rela) or id=$uid";
        $users=DB::select($sql);

        return view('admin.ks.tr.create',compact('users','info'));

    }


    public function update(Request $request, $id)
    {
        $name = $request->name;//角色名称
        $uid = $request->uid;//绑定的帐号
        if(empty($name)){
            return redirect()->back()->with('tip', '角色名称不能为空');
        }
        $update= [
            'role_name'=>$name
        ];
        //
        if(!empty($uid)){
            if($uid==-1){
                return redirect()->back()->with('tip', '请选择帐号');
            }else{
                $update['uid']=$uid;
            }
        }
        $icon='';
        //重新上传图片,获取新的url
        if ($request->hasFile('icon')) {
            $icon=UploadTool::UploadImgForm($request,'icon');
            if (isset($icon['error'])){
                return redirect()->back()->withInput()->with('upload', $icon['error']);
            }else{
                $icon=$icon['url'];
                $update['role_icon']=$icon;
            }
        }
        if(!empty($icon)){
            $info = DB::table('merchant_role_rela')->where('id', $id)->first();
            $accid=$info->peerid;
            //更新帐号信息
            $url="https://api.netease.im/nimserver/user/updateUinfo.action";
            $data=['icon'=>$icon,'accid'=>$accid];
            $header=imHeader();

            $result=curl_request($url,true,$data,$header);
            $result=json_decode($result,true);
            if($result['code']!=200){
                return redirect()->back()->with('tip', '更新失败(更新网易云帐号信息失败)');
            }
        }

        if (DB::table('merchant_role_rela')->where('id',$id)->update($update)) {
            return redirect()->back()->with('tip', '更新成功');
        }

    }


    public function destroy(Request $request, $id)
    {

        DB::table('merchant_role_rela')->where(['id'=>$id])->delete();
        return response()->json(['msg' => 1]);

    }






}
