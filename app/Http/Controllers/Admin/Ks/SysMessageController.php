<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\UploadTool;

/**
 * 系统消息
 * Class SysMessageController
 * @package App\Http\Controllers\Admin\Ks
 */
class SysMessageController extends BaseController
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
        $where = array();

        if (isset($where_str)) {
            $where[] = ['menu_name', 'like', '%' . $where_str . '%'];

        }

        //条件
        $infos = DB::table('notice_info')->where($where)->where('type', 1)->where('enabled', 1)->orderBy('create_time','DESC')->paginate($this->page_size);

        return view('admin.ks.sysm.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.ks.sysm.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $fb_time=$request->input('fb_time');
//        if (empty($fb_time)){
//            return redirect()->back()->withInput()->with('success', '请选择发布时间');
//        }
        $title=$request->title;
        $intro=$request->intro;
        $content=$request->input('content');
        $is_sync=$request->input('is_sync');

        $id = DB::table('notice_info')->insertGetId([
            'title'=>$title,
            'intro'=>$intro,
            'content'=>$content,
            'type'=>1,
            'is_sync'=>$is_sync,
            'create_time'=>date('Y-m-d H:i:s',time()),
//            'fb_time'=>$fb_time,
            'enabled'=>1
        ]);

        DB::table('notice_info')->where('id', $id)->update(['typeid' => $id]);
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
        $info=DB::table('notice_info')->where('id',$id)->first();
        return view('admin.ks.sysm.create',compact('info'));
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
        $fb_time=$request->input('fb_time');
        if (empty($fb_time)){
            return redirect()->back()->withInput()->with('success', '请选择发布时间');
        }
        $title=$request->title;
        $intro=$request->intro;
        $content=$request->input('content');
        $is_sync=$request->input('is_sync');

        DB::table('notice_info')->where('id',$id)->update([
            'title'=>$title,
            'intro'=>$intro,
            'content'=>$content,
            'is_sync'=>$is_sync,
            'fb_time'=>$fb_time,
        ]);
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
        DB::table('notice_info')->where('id',$id)->delete();
        return response()->json([
            'msg' => 1
        ]);

    }

    /**屏蔽和显示
     * @param $id
     */
    function updateStatus($id){
        $info=DB::table('cfg_menu')->where('id',$id)->first();
        if($info->enabled==1){
            DB::table('cfg_menu')->where('id',$id)->update([
                'enabled'=>0
            ]);
        }else{
            DB::table('cfg_menu')->where('id',$id)->update([
                'enabled'=>1
            ]);
        }
        return response()->json([
            'msg' => 1
        ]);

    }
}
