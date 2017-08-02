<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\UploadTool;

/**
 * 品类热销榜banner设置
 * Class MenuController
 * @package App\Http\Controllers\Admin\Ks
 */
class HotCategoryBannerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $infos=DB::table('cfg_hot_category as a')->select('a.id','a.img','b.cat_name')->leftJoin('cfg_category as b','a.cat_id','=','b.cat_id')->where('a.type',2)->paginate(10);

        return view('admin.ks.hcb.index', ['infos' => $infos, 'page_size' => 10]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats=DB::select('SELECT cat_id,cat_name FROM `cfg_category` WHERE parent_id=0');
        //
        return view('admin.ks.hcb.create',compact('cats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $cat_id=$request->cat_id;
        $img=UploadTool::UploadImg($request,'img','public/upload/img');

        DB::table('cfg_hot_category')->insert([
            'cat_id'=>$cat_id,
            'img'=>$img,
            'type'=>2,
        ]);
        return redirect()->back()->with('success', '添加成功');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $cats=DB::select('SELECT cat_id,cat_name FROM `cfg_category` WHERE parent_id=0');
        $info=DB::table('cfg_hot_category')->where('id',$id)->first();
        return view('admin.ks.hcb.create',compact('info','cats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cat_id=$request->cat_id;
        $img=UploadTool::UploadImg($request,'img','public/upload/img');
        $update=array();
        $update['cat_id']=$cat_id;
        if (!empty($img)){
            $update['img']=$img;
        }
        DB::table('cfg_hot_category')->where('id',$id)->update($update);
        return redirect()->back()->with('success', '更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::table('cfg_hot_category')->where('id',$id)->delete();
        return response()->json([
            'msg' => 1
        ]);

    }

}
