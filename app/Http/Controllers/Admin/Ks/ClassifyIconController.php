<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Tools\UploadTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\Category;


/**
 * 分类图标设置
 * Class ClassifyIconController
 * @package App\Http\Controllers\Admin\Ks
 */
class ClassifyIconController extends BaseController
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

        $where[] = ['uid', '=', 0];
        $where[] = ['fid', '=', 0];
        $where[] = ['utype', '=', 0];
        $where[] = ['enabled', '=', 1];
        if (isset($where_str)) {
            $where[] = ['cname', 'like', '%' . $where_str . '%'];

        }

        //条件
        $infos = DB::table('usay_lbl_classify')->select(['cname', 'cid', 'cicon', 'is_show'])->where($where)->paginate($this->page_size);

        return view('admin.ks.ci.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pid = isset($request->pid) ? $request->pid : 0;
        $level = isset($request->level) ? $request->level : 1;
        return view('admin.ks.ci.create', compact('pid', 'level'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $cname = $request->cname;
        $pid = $request->pid;
        $where = array('cname' => $cname);
        $where['fid'] = $pid;
        $where['uid'] = 0;
        $where['utype'] = 0;
        $where['enabled'] = 1;

        // 字符长度限制
        if (mb_strlen($cname) > 6) {
            return redirect()->back()->withInput()->with('error', '分类图标名称字数不能大于6个汉字');
        }
        //同名判断
        $count = DB::table('usay_lbl_classify')->where($where)->count();
        if (!empty($count)) {
            return redirect()->back()->withInput()->with('error', '存在相同分类图标名称');
        }
        //图片
        $icon = UploadTool::UploadImg($request, 'icon', config('admin.upload_img_path'));
        if ($request->hasFile('icon')) {
            $icon = UploadTool::UploadImgForm($request,'icon');
            if ( isset($icon['error']) ){
                return redirect()->back()->withInput()->with('upload', $icon['error']);
            }
        }else{
            return redirect()->back()->withInput()->with('upload', '请上传图片');
        }

        $insert = [
            'cname' => $cname,
            'cicon' => $icon['url'],
            'fid' => $pid,
            'uid' => 0,
            'utype' => 0,
            'createtime' => date('Y-m-d H:i:s', time())
        ];

        if (DB::table('usay_lbl_classify')->insert($insert)) {
            return redirect()->back()->with('success', '添加成功');
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
    public function edit($id, Request $request)
    {
        $pid = isset($request->pid) ? $request->pid : 0;
        $level = isset($request->level) ? $request->level : 1;
        $info = DB::table('usay_lbl_classify')->where('cid', $id)->first();
        $info->url = route('admin.ks.ci.update', $id);
        return view('admin.ks.ci.create', compact('info', 'pid', 'level'));
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
        $cname = $request->cname;
        //同名判断
        $where = array();
        $where[] = ['cname', '=', $cname];
        $where[] = ['cid', '!=', $id];
        $where[] = ['fid', '=', $request->pid];
        $where[] = ['uid', '=', 0];
        $where[] = ['utype', '=', 0];
        $where[] = ['enabled', '=', 1];

        // 字符长度限制
        if (mb_strlen($cname) > 6) {
            return redirect()->back()->withInput()->with('error', '分类图标名称字数不能大于6个汉字');
        }

        $count = DB::table('usay_lbl_classify')->where($where)->count();
        if (!empty($count)) {
            return redirect()->back()->with('error', '存在相同分类图标名称');
        }

        if ($request->hasFile('icon')) {
            $icon = UploadTool::UploadImgForm($request,'icon');
            if (isset($icon['error'])){
                return redirect()->back()->with('upload', $icon['error']);
            }
        }

        //更新的数据
        $update['cname'] = $cname;
        $update['updatetime'] = date('Y-m-d H:i:s', time());
        //有重新上传图片，才更新
        if (!empty($icon)) {
            $update['cicon'] = $icon['url'];
        }

        DB::table('usay_lbl_classify')->where('cid', $id)->update($update);
        return redirect()->back()->with('success', '更新成功');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $where[] = ['uid', '=', 0];
        $where[] = ['utype', '=', 0];
        $where[] = ['enabled', '=', 1];

        //条件
        $sub_classifys = DB::table('usay_lbl_classify')->where($where)->select('cname', 'cid as id', 'cicon', 'fid as pid')->get()->toArray();
        $sub_classifys = array_map('get_object_vars', $sub_classifys);
        $sub_classifys = Category::toLevel($sub_classifys, $id);

        $sub = array_column($sub_classifys, 'id');
        $sub[] = $id;
        DB::table('usay_lbl_classify')->whereIn('cid', $sub)->update([
            'enabled' => 0
        ]);

        return response()->json(['msg' => 1]);
    }

    function batch_destroy(Request $request)
    {
        $ids = $request->ids;

        $where[] = ['uid', '=', 0];
        $where[] = ['utype', '=', 0];
        $where[] = ['enabled', '=', 1];

        //条件
        $sub_classifys = DB::table('usay_lbl_classify')->where($where)->select('cname', 'cid as id', 'cicon', 'fid as pid')->get()->toArray();
        $sub_classifys = array_map('get_object_vars', $sub_classifys);

        $sub_classify_list = array();
        foreach ($ids as $id) {
            $sub_classify_tmp = Category::toLevel($sub_classifys, $id);
            $sub_classify_list = array_merge($sub_classify_list, $sub_classify_tmp);
        }


        $sub = array_column($sub_classify_list, 'id');
        $sub = array_merge($sub, $ids);
        DB::table('usay_lbl_classify')->whereIn('cid', $sub)->update([
            'enabled' => 0
        ]);

        return response()->json(['msg' => 1]);
    }

    /**
     * 展示子分类
     */
    function showSub(Request $request, $id)
    {

        $where_str = $request->where_str;
        $where = array();

        $where[] = ['fid', '=', $id];
        $where[] = ['uid', '=', 0];
        $where[] = ['utype', '=', 0];
        $where[] = ['enabled', '=', 1];
        if (isset($where_str)) {
            $where[] = ['cname', 'like', '%' . $where_str . '%'];
        }

        $where_all = array();
        $where_all[] = ['uid', '=', 0];
        $where_all[] = ['utype', '=', 0];
        $where_all[] = ['enabled', '=', 1];

        //条件
        $sub_classifys = DB::table('usay_lbl_classify')->where($where_all)->select('cname', 'cid as id', 'fid as pid')->get()->toArray();
        $sub_classifys = array_map('get_object_vars', $sub_classifys);
        $sub = Category::getParents($sub_classifys, $id);
        $sub[] = array_column($sub_classifys, null, 'id')[$id];

        $parent = array();
        if ($sub) {
            $sub = array_column($sub, 'cname');
            $parent = implode('-->', $sub);
        }

        //条件
        $infos = DB::table('usay_lbl_classify')->select(['cname', 'cid', 'cicon', 'is_show'])->where($where)->paginate($this->page_size);

        return view('admin.ks.ci.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str, 'level' => $request->level, 'pid' => $id, 'parent' => $parent]);

    }

    /**
     * 屏蔽和显示
     * @param $id
     */
    function updateStatus($id){
        $info = DB::table('usay_lbl_classify')->where('cid',$id)->first();
        if($info->is_show == 1){
            DB::table('usay_lbl_classify')->where('cid',$id)->update([
                'is_show'=>0
            ]);
        }else{
            DB::table('usay_lbl_classify')->where('cid',$id)->update([
                'is_show'=>1
            ]);
        }

        return response()->json(['msg' => 1]);
    }
}
