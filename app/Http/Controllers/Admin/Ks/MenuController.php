<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\UploadTool;

/**首页图标设置
 * Class MenuController
 * @package App\Http\Controllers\Admin\Ks
 */
class MenuController extends BaseController
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
        $infos = DB::table('cfg_menu')->where($where)->paginate($this->page_size);

        return view('admin.ks.menu.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.ks.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $menu_name=$request->menu_name;
        $m_url=$request->m_url;
        $icon=UploadTool::UploadImg($request,'icon','public/upload/img');

        DB::table('cfg_menu')->insert([
            'menu_name'=>$menu_name,
            'm_url'=>$m_url,
            'icon'=>$icon,
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
        $info=DB::table('cfg_menu')->where('id',$id)->first();
        return view('admin.ks.menu.create',compact('info'));
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
        $menu_name=$request->menu_name;
        $m_url=$request->m_url;
        $icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        DB::table('cfg_menu')->where('id',$id)->update([
            'menu_name'=>$menu_name,
            'm_url'=>$m_url,
            'icon'=>$icon,
        ]);
        return redirect()->back()->with('success', '添加成功');
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
        DB::table('cfg_menu')->where('id',$id)->delete();
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
