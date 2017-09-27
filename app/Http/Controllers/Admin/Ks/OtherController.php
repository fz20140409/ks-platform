<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Tools\UploadTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;


/**
 * 杂七杂八
 * Class OtherController
 * @package App\Http\Controllers\Admin\Ks
 */
class OtherController extends BaseController
{
    //上传材料范例
    function material_example()
    {

        $yy = DB::select('select *  from merchant_file  where filetype=2 and sr_id=0')[0];
        $sc = DB::select('select *  from merchant_file  where filetype=5 and sr_id=0')[0];
        $sp = DB::select('select *  from merchant_file  where filetype=4 and sr_id=0')[0];
        return view('admin.ks.other.material_example', compact('yy', 'sc', 'sp'));
    }

    //上传材料范例
    function material_example_update(Request $request)
    {
        $remark = $request->remark;
        $yy = UploadTool::UploadImg($request, 'yy', 'public/upload/img');
        $sc = UploadTool::UploadImg($request, 'sc', 'public/upload/img');
        $sp = UploadTool::UploadImg($request, 'sp', 'public/upload/img');
        $data_yy['remark'] = $remark;
        $data_sc['remark'] = $remark;
        $data_sp['remark'] = $remark;
        if (!empty($yy)) {
            $data_yy['fileurl'] = $yy;
        }
        if (!empty($sc)) {
            $data_sc['fileurl'] = $sc;
        }
        if (!empty($sp)) {
            $data_sp['fileurl'] = $sp;
        }
        DB::table('merchant_file')->whereRaw('filetype=2 and sr_id=0')->update($data_yy);
        DB::table('merchant_file')->whereRaw('filetype=5 and sr_id=0')->update($data_sc);
        DB::table('merchant_file')->whereRaw('filetype=4 and sr_id=0')->update($data_sp);
        return redirect()->back()->with('success', '操作成功');

    }

    //模块设置
    function module_settings()
    {
        //优惠头条
        $yhtt=DB::table('cfg_menu')->where('id',5)->first();
        //合作机会
        $hzjh=DB::table('cfg_menu')->where('id',4)->first();
        //优质厂商
        $yzcs=DB::table('cfg_menu')->where('id',2)->first();
        //热门商品
        $rmsp=DB::table('cfg_menu')->where('id',1)->first();


        return view('admin.ks.other.module_settings',compact('yhtt','hzjh','yzcs','rmsp'));

    }
    //模块设置
    function do_module_settings(Request $request){
        //优惠头条
        $yhtt=$request->yhtt;
        //合作机会
        $hzjh=$request->hzjh;
        //优质厂商
        $yzcs=$request->yzcs;
        //热门商品
        $rmsp=$request->rmsp;
        DB::table('cfg_menu')->where('id',5)->update(['enabled'=>$yhtt]);
        DB::table('cfg_menu')->where('id',4)->update(['enabled'=>$hzjh]);
        DB::table('cfg_menu')->where('id',2)->update(['enabled'=>$yzcs]);
        DB::table('cfg_menu')->where('id',1)->update(['enabled'=>$rmsp]);
        return redirect()->back()->with('success', '操作成功');





    }
    //个人中心
    function user_center(){
        $user=Auth::user();
        return view('admin.ks.other.user_center',compact('user'));
    }
    //个人中心
    function do_user_center(Request $request){
        //
        $table=config('entrust.users_table');
        $user = User::findOrFail(Auth::id());
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:$table,email,$user->id",
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        DB::beginTransaction();
        try {
            $data = ['name' => $request->name, 'email' => $request->email];
            $avatar=UploadTool::UploadImg($request,'avatar','public/avatars');
            if(!empty($avatar)){
                $data['avatar']=$avatar;
            }
            //不填密码，不更新原密码
            if (!empty($request->password)) {
                $data['password'] = bcrypt($request->password);
            }
            $user->update($data);

            DB::commit();
            return redirect()->back()->with('success', '更新成功');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', '更新失败');
        }

    }

    // 客服设置
    function kefu_setting()
    {
        $kefu = DB::table('cfg_kefu')->where('sr_id', 0)->where('enabled', 1)->first();
        return view('admin.ks.other.kefu_setting', compact('kefu'));
    }

    // 客服设置
    function kefu_setting_update(Request $request)
    {
        $data['tel'] = $request->tel;

        $kefu = DB::table('cfg_kefu')->where('sr_id', 0)->where('enabled', 1)->first();
        if ($kefu) {
            DB::table('cfg_kefu')->where('sr_id', 0)->where('enabled', 1)->update($data);
        } else {
            DB::table('cfg_kefu')->where('sr_id', 0)->where('enabled', 1)->insert($data);
        }

        return redirect()->back()->with('success', '操作成功');

    }

    //客商平台服务协议
    function service_contract()
    {
        $content = DB::table('cfg_platform_content')->where('type', 1)->where('enabled', 1)->value('content');
        return view('admin.ks.other.service_contract', compact('content'));
    }

    //客商平台服务协议
    function service_contract_update(Request $request)
    {
        $content = $request->service_contract;
        $platform_content = DB::table('cfg_platform_content')->where('type', 1)->where('enabled', 1)->first();
        if ($platform_content) {
            DB::table('cfg_platform_content')->where('type', 1)->where('enabled', 1)->update(['content' => $content]);
        } else {
            $insert = array(
                'type' => 1,
                'content' => $content,
                'create_time' => date("Y-m-d H:i:s")
            );
            DB::table('cfg_platform_content')->where('type', 1)->where('enabled', 1)->insert($insert);
        }

        return redirect()->back()->with('success', '操作成功');

    }

    //隐私声明
    function privacy_policy()
    {
        $content = DB::table('cfg_platform_content')->where('type', 2)->where('enabled', 1)->value('content');
        return view('admin.ks.other.privacy_policy', compact('content'));
    }

    //隐私声明
    function privacy_policy_update(Request $request)
    {
        $content = $request->privacy_policy;
        $platform_content = DB::table('cfg_platform_content')->where('type', 2)->where('enabled', 1)->first();
        if ($platform_content) {
            DB::table('cfg_platform_content')->where('type', 2)->where('enabled', 1)->update(['content' => $content]);
        } else {
            $insert = array(
                'type' => 2,
                'content' => $content,
                'create_time' => date("Y-m-d H:i:s")
            );
            DB::table('cfg_platform_content')->where('type', 2)->where('enabled', 1)->insert($insert);
        }

        return redirect()->back()->with('success', '操作成功');

    }
}
