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
        $infos = DB::table('usay_lbl_classify')->select(['cname', 'cid', 'cicon'])->where($where)->paginate($this->page_size);

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

        //同名判断
        $count = DB::table('usay_lbl_classify')->where($where)->count();
        if (!empty($count)) {
            return redirect()->back()->withInput()->with('success', '存在相同分类图标名称');

        }
        //图片
        $icon = UploadTool::UploadImg($request, 'icon', config('admin.upload_img_path'));
        $flag = $request->flag;

        if (empty($icon) && empty($flag)) {
            return redirect()->back()->with('upload', '请上传图片');

        }

        $insert = [
            'cname' => $cname,
            'cicon' => $icon,
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
        $where[] = ['uid', '=', 0];
        $where[] = ['utype', '=', 0];
        $where[] = ['enabled', '=', 1];
        $count = DB::table('usay_lbl_classify')->where($where)->count();
        if (!empty($count)) {
            return redirect()->back()->with('success', '存在相同分类图标名称');
        }
        $icon = UploadTool::UploadImg($request, 'icon', config('admin.upload_img_path'));
        //更新的数据
        $update['cname'] = $cname;
        $update['updatetime'] = date('Y-m-d H:i:s', time());
        //有重新上传图片，才更新
        if (!empty($icon)) {
            $update['cicon'] = $icon;
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
        $sub_classifys = DB::table('usay_lbl_classify')->where($where)->get(['cname', 'cid', 'cicon', 'fid as pid'])->toArray();

//        $sub = array();
//        //有子菜单
//        if (!empty($sub_classifys)) {
//            foreach ($sub_classifys as $sub_classify) {
//                $sub[] = $sub_classify['id'];
//            }
//            $sub[] = $sub_classify->cid;
//        } else {
//            //无子菜单
//            $sub=$sub_classifys->cid;
//        }
//        DB::beginTransaction();
//        try {
//            $sub_classify->roles()->detach($sub);
//            Permission::destroy($sub);
//            DB::commit();
//            return response()->json([
//                'msg' => 1
//            ]);
//        } catch (\Exception $exception) {
//            Log::error($exception->getMessage());
//            DB::rollBack();
//            return response()->json([
//                'msg' => 0
//            ]);
//        }

    }

    function batch_destroy(Request $request)
    {
        $ids = $request->ids;

        //确认删除子分类
        if (isset($request->flag)) {
            //当前分类下，子分类
            $infos = DB::table('usay_lbl_classify')->whereIn('fid', $ids)->select('cid')->get()->toArray();
            $idss = array();
            foreach ($infos as $info) {
                $idss[] = $info->cid;
            }
            DB::table('usay_lbl_classify')->whereIn('fid', $idss)->update([
                'enabled' => 0
            ]);//子分类的子分类
            DB::table('usay_lbl_classify')->whereIn('cid', $idss)->update([
                'enabled' => 0
            ]);//子分类
            DB::table('usay_lbl_classify')->whereIn('cid', $ids)->update([
                'enabled' => 0
            ]);//当前分类

            return response()->json(['msg' => 1]);
        }
        $count = DB::table('usay_lbl_classify')->whereIn('fid', $ids)->where('enabled', 1)->count();
        if (!empty($count)) {
            return response()->json(['msg' => '该分类下有子分类，是否一起删除?']);
        }

        DB::table('usay_lbl_classify')->whereIn('cid', $ids)->update([
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
        if ($request->level == 2) {
            $parent = DB::table('usay_lbl_classify')->where('cid', $id)->select('cname')->get()[0]->cname;
        }
        if ($request->level == 3) {
            $name1 = DB::table('usay_lbl_classify')->where('cid', $id)->select('cname')->get()[0];
            $name2 = DB::select("SELECT b.cname FROM `usay_lbl_classify` AS a LEFT JOIN usay_lbl_classify AS b ON a.fid=b.cid WHERE a.cid=$id")[0];
            $parent = $name2->cname . "-->" . $name1->cname;

        }
        //条件
        $infos = DB::table('usay_lbl_classify')->select(['cname', 'cid', 'cicon'])->where($where)->paginate($this->page_size);

        return view('admin.ks.ci.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str, 'level' => $request->level, 'pid' => $id, 'parent' => $parent]);

    }
}
