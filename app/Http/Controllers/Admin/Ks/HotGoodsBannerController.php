<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Tools\UploadTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * 首页热门商品设置
 * Class HotGoodsBannerController
 * @package App\Http\Controllers\Admin\Ks
 */
class HotGoodsBannerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $infos = DB::table('cfg_hot_category as a')->select('a.id','a.img','b.cat_name')->leftJoin('cfg_category as b','a.cat_id','=','b.cat_id')->where('a.type',1)->get();

        return view('admin.ks.hgb.index',compact('infos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $info=DB::table('cfg_hot_category')->where('id',$id)->first();
        $cats=DB::select('SELECT cat_id,cat_name FROM cfg_category WHERE parent_id=0 AND cat_id NOT in(SELECT cat_id FROM cfg_hot_category WHERE type=1)');

        return view('admin.ks.hgb.create',compact('cats','info'));
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
        $icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        $data=array();
        $data['cat_id']=$cat_id;
        if (!empty($icon)){
            $data['img']=$icon;
        }
        DB::table('cfg_hot_category')->where('id',$id)->update($data);
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
    }
}
