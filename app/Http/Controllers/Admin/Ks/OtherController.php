<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Tools\UploadTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



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
        $remark=$request->remark;
        $yy=UploadTool::UploadImg($request,'yy','public/upload/img');
        $sc=UploadTool::UploadImg($request,'sc','public/upload/img');
        $sp=UploadTool::UploadImg($request,'sp','public/upload/img');
        $data_yy['remark']=$remark;
        $data_sc['remark']=$remark;
        $data_sp['remark']=$remark;
        if(!empty($yy)){
            $data_yy['fileurl']=$yy;
        }
        if(!empty($sc)){
            $data_sc['fileurl']=$sc;
        }
        if(!empty($sp)){
            $data_sp['fileurl']=$sp;
        }
        DB::table('merchant_file')->whereRaw('filetype=2 and sr_id=0')->update($data_yy);
        DB::table('merchant_file')->whereRaw('filetype=5 and sr_id=0')->update($data_sc);
        DB::table('merchant_file')->whereRaw('filetype=4 and sr_id=0')->update($data_sp);
        return redirect()->back()->with('success', '操作成功');

    }

}
