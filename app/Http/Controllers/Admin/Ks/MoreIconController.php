<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\UploadTool;

/**
 *与我有关-更多图标设置
 * Class MoreIconController
 * @package App\Http\Controllers\Admin\Ks
 */
class MoreIconController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //
        $where_str = $request->where_str;
        //类型
        $type=isset($request->type)?$request->type:-1;
        $where_link=['page_size'=>$this->page_size];
        $where = array();
        if (isset($where_str)) {
            $where[] = ['name', 'like', '%' . $where_str . '%'];
            $where_link['where_str']=$where_str;

        }
        if ($type!=-1) {
            $where[] = ['type', '=', "$type"];
            $where_link['type']=$type;

        }


        //条件
        $infos = DB::table('cfg_user_function')->where($where)->paginate($this->page_size);

        return view('admin.ks.micon.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str,'where_link'=>$where_link,'type'=>$type]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.ks.micon.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $name=$request->name;
        $type=$request->type;
        $icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        if (empty($icon)){
            return redirect()->back()->withInput()->with('upload', '请上传图标');
        }

        DB::table('cfg_user_function')->insert([
            'name'=>$name,
            'enabled'=>1,
            'icon'=>$icon,
            'type'=>$type,
            'status'=>1
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
        $info=DB::table('cfg_user_function')->where('id',$id)->first();
        return view('admin.ks.micon.create',compact('info'));
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
        $name=$request->name;
        $type=$request->type;
        $icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        $update=['name'=>$name, 'type'=>$type];
        if (!empty($icon)){
            $update['icon']=$icon;
        }
        DB::table('cfg_user_function')->where('id',$id)->update($update);
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
        DB::table('cfg_user_function')->where('id',$id)->delete();
        return response()->json([
            'msg' => 1
        ]);

    }

    /**屏蔽和显示
     * @param $id
     */
    function updateStatus($id){
        $info=DB::table('cfg_user_function')->where('id',$id)->first();
        if($info->status==1){
            DB::table('cfg_user_function')->where('id',$id)->update([
                'status'=>0
            ]);
        }else{
            DB::table('cfg_user_function')->where('id',$id)->update([
                'status'=>1
            ]);
        }
        return response()->json([
            'msg' => 1
        ]);

    }
}
