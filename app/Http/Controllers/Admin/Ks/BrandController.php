<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\Category;
use App\Http\Controllers\Tools\UploadTool;
use Illuminate\Support\Facades\Log;


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
        $infos = DB::table('cfg_brand')->where($where)->paginate($this->page_size);

        return view('admin.ks.brand.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $infos = DB::select('SELECT cat_id AS id,cat_name,parent_id AS pid FROM `cfg_category` WHERE parent_id=0 and enabled=1 UNION SELECT cat_id,cat_name,parent_id FROM cfg_category WHERE parent_id IN(SELECT cat_id FROM cfg_category WHERE parent_id=0)');
        $infos = json_decode(json_encode($infos), true);
        $infos = Category::toLayer($infos);
        return view('admin.ks.brand.create', compact('infos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zybrand = $request->zybrand;
        $count = DB::table('cfg_brand')->where("zybrand",$zybrand)->count();
        if (!empty($count)) {
            return redirect()->back()->withInput()->with('success', '品牌名称不允许重名');
        }
        $ids = $request->ids;
        if (empty($ids)) {
            return redirect()->back()->withInput()->with('success', '请选择所属品类');

        }
        /*$icon = UploadTool::UploadImg($request, 'icon', config('admin.upload_img_path'));
        if (empty($icon)) {
            return redirect()->back()->withInput()->with('upload', '请上传图标');
        }*/
        if ($request->hasFile('icon')) {
            $icon=UploadTool::UploadImgForm($request,'icon');
            if (isset($icon['error'])){
                return redirect()->back()->withInput()->with('upload', $icon['error']);
            }
        }else{
            return redirect()->back()->withInput()->with('upload', '请上传图标');
        }

        DB::beginTransaction();
        try{
            $id = DB::table('cfg_brand')->insertGetId([
                'zybrand' => $zybrand,
                'bicon' => $icon['url'],
            ]);
            $data = array();
            foreach ($ids as $item) {
                $data[] = array('brand_id' => $id, 'cat_id' => $item);

            }
            DB::table('brand_category_rela')->insert($data);

            DB::commit();
            return redirect()->back()->with('success', '添加成功');

        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', '添加失败');
        }






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
        return view('admin.ks.user_info.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $infos = DB::select('SELECT cat_id AS id,cat_name,parent_id AS pid FROM `cfg_category` WHERE parent_id=0 AND enabled=1 UNION SELECT cat_id,cat_name,parent_id FROM cfg_category WHERE parent_id IN(SELECT cat_id FROM cfg_category WHERE parent_id=0)');
        $infos = json_decode(json_encode($infos), true);
        $infos = Category::toLayer($infos);
        $info = DB::table('cfg_brand')->where('bid', $id)->first();
        $cat_ids = DB::table('brand_category_rela')->where('brand_id', $id)->pluck('cat_id');
        $cat_ids = json_decode(json_encode($cat_ids), true);
        return view('admin.ks.brand.create', compact('infos', 'info', 'cat_ids'));
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
        //品牌同名检测
        $zybrand = $request->zybrand;
        $where[] = ['zybrand', '=', $zybrand];
        $where[] = ['bid', '!=', $id];
        $count = DB::table('cfg_brand')->where($where)->count();
        if (!empty($count)) {
            return redirect()->back()->withInput()->with('success', '品牌名称不允许重名');
        }

        //品类
        $ids = isset($request->ids) ? $request->ids : array();
        if (empty($ids)) {
            return redirect()->back()->withInput()->with('success', '请选择所属品类');

        }
        //$icon = UploadTool::UploadImg($request, 'icon', config('admin.upload_img_path'));



        DB::beginTransaction();
        try{
            //品牌更新的数据
            $update['zybrand'] = $zybrand;
            if (!empty($icon)) {
                $update['bicon'] = $icon;
            }
            DB::table('cfg_brand')->where('bid', $id)->update($update);

            //原数据
            $cat_ids = DB::table('brand_category_rela')->where('brand_id', $id)->pluck('cat_id');
            if (!empty($cat_ids)) {
                $cat_ids = json_decode(json_encode($cat_ids), true);
                //数据变化
                if (!empty(array_diff($cat_ids, $ids))) {
                    //删除原数据
                    DB::table('brand_category_rela')->where('brand_id', $id)->delete();

                }
            }
            $data = array();
            foreach ($ids as $item) {
                $data[] = array('brand_id' => $id, 'cat_id' => $item);

            }
            if (!empty($data)) {
                //更新品牌和品类关联
                DB::table('brand_category_rela')->insert($data);
            }

            DB::commit();
            return redirect()->back()->with('success', '更新成功');

        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', '更新失败');
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //商品，品牌的关联
        $count=DB::table('goods')->where('bid', $id)->count();
        if(!empty($count)){
            return response()->json(['msg' => -1,'info'=>'无法删除，已被商品使用']);
        }
        //
        DB::beginTransaction();
        try{
            DB::table('cfg_brand')->where('bid', $id)->delete();
            DB::table('brand_category_rela')->where('brand_id', $id)->delete();
            DB::commit();
            return response()->json([
                'msg' => 1
            ]);

        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            DB::rollBack();
            return response()->json([
                'msg' => 0
            ]);
        }

    }

    function batch_destroy(Request $request)
    {
        $ids = $request->ids;
        DB::beginTransaction();
        try{
            DB::table('cfg_brand')->whereIn('bid', $ids)->delete();
            DB::table('brand_category_rela')->whereIn('brand_id', $ids)->delete();
            DB::commit();
            return response()->json([
                'msg' => 1
            ]);

        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            DB::rollBack();
            return response()->json([
                'msg' => 0
            ]);
        }




    }

}
