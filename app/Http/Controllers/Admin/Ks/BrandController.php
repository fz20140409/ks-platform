<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\Category;
use App\Http\Controllers\Tools\UploadTool;


class BrandController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where_str = $request->where_str;
        $where = array();

        if (isset($where_str)) {
            $where[] = ['zybrand', 'like', '%' . $where_str . '%'];

        }

        //条件
        $infos=DB::table('cfg_brand')->where($where)->paginate($this->page_size);

        return view('admin.ks.brand.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,'where_str' => $where_str]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $infos=DB::select('SELECT cat_id AS id,cat_name,parent_id AS pid FROM `cfg_category` WHERE parent_id=0 UNION SELECT cat_id,cat_name,parent_id FROM cfg_category WHERE parent_id IN(SELECT cat_id FROM cfg_category WHERE parent_id=0)');
        $infos=json_decode(json_encode($infos), true);
        $infos=Category::toLayer($infos);
        return view('admin.ks.brand.create',compact('infos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zybrand=$request->zybrand;
        $ids=$request->ids;
        $icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        $id=DB::table('cfg_brand')->insertGetId([
           'zybrand'=>$zybrand,
           'bicon'=>$icon,
       ]);
        $data=array();
        if (!empty($ids)){
            foreach ($ids as $item){
                $data[]=array('brand_id'=>$id,'cat_id'=>$item);

            }

            DB::table('brand_category_rela')->insert($data);

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
        return view('admin.ks.user_info.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $infos=DB::select('SELECT cat_id AS id,cat_name,parent_id AS pid FROM `cfg_category` WHERE parent_id=0 UNION SELECT cat_id,cat_name,parent_id FROM cfg_category WHERE parent_id IN(SELECT cat_id FROM cfg_category WHERE parent_id=0)');
        $infos=json_decode(json_encode($infos), true);
        $infos=Category::toLayer($infos);
        $info=DB::table('cfg_brand')->where('bid',$id)->first();
        $cat_ids=DB::table('brand_category_rela')->where('brand_id',$id)->pluck('cat_id');
        $cat_ids=json_decode(json_encode($cat_ids), true);
        return view('admin.ks.brand.create',compact('infos','info','cat_ids'));
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
        $zybrand=$request->zybrand;
        $ids=isset($request->ids)?$request->ids:array();
        $icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        //品牌更新的数据
        $update['zybrand']=$zybrand;
        if(!empty($icon)){
            $update['bicon']=$icon;
        }
        DB::table('cfg_brand')->where('bid',$id)->update($update);

        //原数据
        $cat_ids=DB::table('brand_category_rela')->where('brand_id',$id)->pluck('cat_id');
        if(!empty($cat_ids)){
            $cat_ids=json_decode(json_encode($cat_ids), true);
            //数据变化
            if (!empty(array_diff($cat_ids,$ids))){
                //删除原数据
                DB::table('brand_category_rela')->where('brand_id',$id)->delete();

            }
        }
        $data=array();
        foreach ($ids as $item){
            $data[]=array('brand_id'=>$id,'cat_id'=>$item);

        }
        if (!empty($data)){
            //更新品牌和品类关联
            DB::table('brand_category_rela')->insert($data);
        }


        return redirect()->back()->with('success', '更新成功');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::table('cfg_brand')->where('bid',$id)->delete();
        DB::table('brand_category_rela')->where('brand_id',$id)->delete();

        return response()->json([
            'msg' => 1
        ]);
    }

    function batch_destroy(Request $request){
        $ids = $request->ids;

        DB::table('cfg_brand')->whereIn('bid',$ids)->delete();
        DB::table('brand_category_rela')->whereIn('brand_id',$ids)->delete();
        return response()->json([
            'msg' => 1
        ]);


    }

}
