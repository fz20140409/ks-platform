<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\UploadTool;
use  Illuminate\Support\Facades\Log;

/**
 * 优惠头条
 * Class DiscountHeadlinesController
 * @package App\Http\Controllers\Admin\Ks
 */
class DiscountHeadlinesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $where_link = array();
        $str_where = '';
        //时间
        $stime=$request->stime;
        $etime=$request->etime;
        if(isset($stime)&&isset($etime)&&$stime>$etime){
            return redirect()->with('success', '起始时间大于结束时间');
        }
        //起始时间
        if(isset($stime)){
            $str_where .= " and a.createtime>='$stime'";
            $where_link['stime'] = $stime;
        }
        //结束时间
        if(isset($etime)){
            $str_where .= " and a.createtime<='$etime'";
            $where_link['etime'] = $etime;
        }

        //分类
        $cate = isset($request->cate) ? $request->cate : -1;

        if ($cate != -1) {
            $str_where .= " and b.cid=$cate";
            $where_link['cate'] = $cate;

        }
        //状态
        $status = isset($request->status) ? $request->status : -1;
        if ($status != -1) {
            $str_where .= " and a.enabled=$status";
            $where_link['status'] = $status;
        }
        //标题
        $title = $request->title;
        if (isset($title)) {
            $str_where .= " and a.title like '%$title%'";
            $where_link['title'] = $title;
        }
        $cates = DB::select("SELECT * FROM cfg_preferential_cate");

        $sql = "(SELECT a.hid, a.createtime ,a.title,(SELECT catename FROM cfg_preferential_cate WHERE id=b.cid ) catename,a.view_count,a.has_good,a.optimize_count,(SELECT COUNT(*) FROM headline_attr WHERE hid=a.hid AND enabled=1 and attr_type='good') num,a.enabled,a.is_top FROM `headline_info` AS a LEFT JOIN headline_cate AS b ON a.hid=b.hid where 1=1 $str_where order by a.createtime desc ) as d";
        $infos = DB::table(DB::raw($sql))->paginate($this->page_size);
        return view('admin.ks.dh.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_link' => $where_link, 'cates' => $cates, 'cate' => $cate, 'status' => $status, 'title' => $title,
            'stime'=>$stime,'etime'=>$etime
            ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cates = DB::table('cfg_preferential_cate')->get();
        $areas = DB::table('cfg_locations')->select('id', 'name')->where('level', 1)->get();

        return view('admin.ks.dh.create', compact('cates', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        if ($request->ajax()) {
            //视频
            $video = UploadTool::UploadVideo($request, 'video', 'public/upload/video');
            if (!empty($video)) {
                return response()->json(['url' => $video]);
            }
        }

        //本地上传视频的url
        $url = $request->url;
        if (empty($url)) {
            //填写的地址
            $url = $request->video_url;
        }
        //图片集
        $icons = UploadTool::UploadMultipleImg($request, 'icon', 'public/upload/img');
        $vd_icons = UploadTool::UploadMultipleImg($request, 'vd_icon', 'public/upload/img');

        $title = $request->title;
        //$is_top = $request->is_top;
        $cate = $request->cate;
        $has_good = $request->has_good;
        $display_type = $request->display_type;
        $intro = $request->intro;
        $area = $request->area;
        $keyword = $request->keyword;
        if (empty($area)) {
            return redirect()->back()->with('success', '请选择发布范围');
        }
        DB::beginTransaction();
        try {
            $insert = [
                'title' => $title,
               /* 'is_top' => $is_top,*/
                'has_good' => $has_good,
                'display_type' => $display_type,
                'intro' => $intro,
                'keyword'=>$keyword,
                'createtime' => date('Y-m-d H:i:s', time())
            ];
            $id = DB::table('headline_info')->insertGetId($insert);
            //分类
            DB::table('headline_cate')->insert([
                'hid' => $id,
                'cid' => $cate,
                'enabled' => 1
            ]);
            //发布区域
            foreach ($area as $item) {
                DB::table('headline_area_range')->insert([
                    'hid' => $id,
                    'area_id' => $item,
                    'enabled' => 1
                ]);
            }
            //视频
            if (empty($url)){
                $url='';
            }
            $vd=[
                'video_type' => $request->video_type,
                'hid' => $id,
                'attr_value' => $url,
                'enabled' => 1,
                'attr_type' => 'mv',
                'create_time' => date('Y-m-d H:i:s', time())
            ];
            if (!empty($vd_icons)){
                $vd_icons=implode(',',$vd_icons);
                $vd['remark']=$vd_icons;

            }
            DB::table('headline_attr')->insert($vd);

            //图集
            if (!empty($icons)) {
                foreach ($icons as $icon) {
                    DB::table('headline_attr')->insert([
                        'hid' => $id,
                        'attr_value' => $icon,
                        'attr_type' => 'img',
                        'enabled' => 1,
                        'create_time' => date('Y-m-d H:i:s', time())
                    ]);
                }

            }
            DB::commit();
            return redirect()->back()->with('success', '添加成功');
        } catch (\Exception $exception) {
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = DB::table("headline_attr")->where('hid', $id)->where('attr_type', 'mv')->first();
        $imgs = DB::table("headline_attr")->where('hid', $id)->where('attr_type', 'img')->pluck('attr_value')->toArray();
        $info = DB::table("headline_info")->where('hid', $id)->first();
        $cate = DB::table("headline_cate")->where('hid', $id)->first();
        $area_arr = DB::table("headline_area_range")->where('hid', $id)->pluck('area_id')->toArray();

        $cates = DB::table('cfg_preferential_cate')->get();
        $areas = DB::table('cfg_locations')->select('id', 'name')->where('level', 1)->get();


        return view('admin.ks.dh.create', compact('cates', 'areas', 'info', 'area_arr', 'cate', 'video', 'imgs'));
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
        if ($request->ajax()) {
            //视频
            $video = UploadTool::UploadVideo($request, 'video', 'public/upload/video');
            if (!empty($video)) {
                return response()->json(['url' => $video]);
            }
        }

        //图片集
        $icons = UploadTool::UploadMultipleImg($request, 'icon', 'public/upload/img');
        $vd_icons = UploadTool::UploadMultipleImg($request, 'vd_icon', 'public/upload/img');
        $url=isset($request->url)?$request->url:'';
        $video_url=isset($request->video_url)?$request->video_url:'';
        $title = $request->title;
       /* $is_top = $request->is_top;*/
        $cate = $request->cate;
        $has_good = $request->has_good;
        $display_type = $request->display_type;
        $intro = $request->intro;
        $area = $request->area;
        $keyword = $request->keyword;

        if (empty($area)) {
            return redirect()->back()->with('success', '请选择发布范围');
        }
        $update = [
            'title' => $title,
         /*   'is_top' => $is_top,*/
            'has_good' => $has_good,
            'display_type' => $display_type,
            'intro' => $intro,
            'keyword'=>$keyword,
            'updatetime' => date('Y-m-d H:i:s', time())
        ];
        DB::beginTransaction();
        try {
            //更新优惠头条
            DB::table('headline_info')->where('hid', $id)->update($update);
            //更新分类
            DB::table('headline_cate')->where('hid', $id)->update([
                'cid' => $cate
            ]);
            //原始地区
            $arr = DB::table("headline_area_range")->where('hid', $id)->pluck('area_id')->toArray();

            if (count($arr)!=count($area)||array_diff($arr,$area)) {
                //删除原始数据
                DB::table("headline_area_range")->where('hid', $id)->delete();
                //发布区域
                foreach ($area as $item) {
                    DB::table('headline_area_range')->insert([
                        'hid' => $id,
                        'area_id' => $item,
                        'enabled' => 1
                    ]);
                }
            }
            if (empty(!$vd_icons)){
                $vd_icons=implode(',',$vd_icons);
                $remark=$vd_icons;
            }else{
                $remark='';
            }

            if ($request->video_type==1){
                //更新url
                DB::table("headline_attr")->where('hid', $id)->where('attr_type', 'mv')->update(['attr_value' => $video_url, 'video_type' => $request->video_type,'remark'=>$remark]);
            }else{
                DB::table("headline_attr")->where('hid', $id)->where('attr_type', 'mv')->update(['attr_value' => $url, 'video_type' => $request->video_type,'remark'=>$remark]);
            }

            //更新图片集
            if (!empty($icons)) {
                //删除原始
                DB::table("headline_attr")->where('hid', $id)->where('attr_type', 'img')->delete();
                //新增
                foreach ($icons as $icon) {
                    DB::table('headline_attr')->insert([
                        'hid' => $id,
                        'attr_value' => $icon,
                        'attr_type' => 'img',
                        'enabled' => 1,
                        'create_time' => date('Y-m-d H:i:s', time())
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', '更新成功');
        }catch (\Exception $exception) {
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
        DB::beginTransaction();
        try {
            //删除优惠头条
            DB::table('headline_info')->where('hid', $id)->delete();
            //删除分类
            DB::table('headline_cate')->where('hid', $id)->delete();
            //删除地区
            DB::table('headline_area_range')->where('hid', $id)->delete();
            //删除图片集
            DB::table('headline_attr')->where('hid', $id)->where('attr_type', 'img')->delete();
            //删除视频
            DB::table('headline_attr')->where('hid', $id)->where('attr_type', 'mv')->delete();
            DB::commit();
            return response()->json([
                'msg' => 1
            ]);
        }catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return response()->json([
                'msg' => 0
            ]);
        }

    }


    function updateStatus($id)
    {
        $info = DB::table('headline_info')->where('hid', $id)->first();
        if ($info->enabled == 1) {
            DB::table('headline_info')->where('hid', $id)->update([
                'enabled' => 0
            ]);
        } else {
            DB::table('headline_info')->where('hid', $id)->update([
                'enabled' => 1
            ]);
        }
        return response()->json([
            'msg' => 1
        ]);

    }

    function getOptimize($id){
        $data=DB::select("SELECT rid,COUNT(*) AS num,b.r_name FROM `user_prcate_reducereason` LEFT JOIN cfg_pr_reducereason AS b ON rid=b.r_id WHERE hid=$id GROUP BY rid");
        return view('admin.ks.coop.optimize',compact('data'));
    }
}
