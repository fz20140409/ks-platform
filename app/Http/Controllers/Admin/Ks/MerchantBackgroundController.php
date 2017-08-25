<?php

namespace App\Http\Controllers\Admin\ks;

use DB;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Tools\UploadTool;

class MerchantBackgroundController extends BaseController
{
    /**
     * 背景及icon设置
     * Class MerchantBackgroundController
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //类型
        $type = isset($request->type) ? $request->type : 1; // 1为平台icon
        $where_link = array('type' => $type, 'page_size' => $this->page_size);

        if ($type == 2) {
            $infos = DB::table('user_card_background')->paginate($this->page_size);
        } else {
            $infos = DB::table('merchant_background')->where('type', $type)->paginate($this->page_size);
        }

        $data = array(
            'infos' => $infos,
            'page_size' => $this->page_size,
            'page_sizes' => $this->page_sizes,
            'where_link' => $where_link,
            'type' => $type
        );

        return view('admin.ks.mb.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pt = DB::table('merchant_background')->where('type', 1)->first();
        return view('admin.ks.mb.create', compact('pt'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $type = $request->type;
        $icon = UploadTool::UploadImg($request,'icon','public/upload/img');
        if (empty($icon)){
            return redirect()->back()->withInput()->with('upload', '请上传图片');
        }

        $data = array(
            'bgurl' => $icon,
            'createtime' => date('Y-m-d H:i:s'),
            'enabled' => 1
        );

        if ($type == 2) {
            DB::table('user_card_background')->insert($data);
        } else {
            $data['type'] = $type;
            DB::table('merchant_background')->insert($data);
        }

        return redirect()->back()->with('success', '添加成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $type = $request->type;
        if ($type == 2) {
            $info = DB::table('user_card_background')->where('id',$id)->first();
        } else {
            $data['type'] = $type;
            $info = DB::table('merchant_background')->where('id',$id)->first();
        }

        return view('admin.ks.mb.create',compact('info', 'type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $old_type = $request->old_type;
        $type = $request->type;
        $icon = UploadTool::UploadImg($request,'icon','public/upload/img');

        // 因为名片图片和网店背景不一样所以修改时需增加删除相应的表记录
        if ($type == 2) {
            if ($old_type == 2) {
                $update = array();
                if (!empty($icon)){
                    $update['bgurl'] = $icon;
                }
                DB::table('user_card_background')->where('id',$id)->update($update);
            } else {
                $data = array(
                    'bgurl' => $icon,
                    'createtime' => date('Y-m-d H:i:s'),
                    'enabled' => 1
                );
                DB::table('user_card_background')->insert($data);
                DB::table('merchant_background')->where('id', $id)->delete();
            }
        } else {
            if ($old_type != 2) {
                $update = array();
                if (!empty($icon)){
                    $update['bgurl'] = $icon;
                }
                $update['type'] = $type;
                DB::table('merchant_background')->where('id',$id)->update($update);
            } else {
                $data = array(
                    'bgurl' => $icon,
                    'createtime' => date('Y-m-d H:i:s'),
                    'enabled' => 1,
                    'type' => $type
                );
                DB::table('merchant_background')->insert($data);
                DB::table('user_card_background')->where('id', $id)->delete();
            }
        }

        return redirect()->back()->with('success', '更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $type = $request->type;
        if ($type == 2) {
            DB::table('user_card_background')->where('id', $id)->delete();
        } else {
            DB::table('merchant_background')->where('id', $id)->delete();
        }

        return response()->json([
            'msg' => 1
        ]);
    }
}
