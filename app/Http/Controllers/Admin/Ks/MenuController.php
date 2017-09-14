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
        $count = DB::table('cfg_menu')->where('menu_name',$menu_name)->count();
        if (!empty($count)) {
            return redirect()->back()->withInput()->with('success', '存在相同名称');
        }
        $m_url=$request->m_url;
        //$icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        if ($request->hasFile('icon')) {
            $icon=UploadTool::UploadImgForm($request,'icon');
            if (isset($icon['error'])){
                return redirect()->back()->withInput()->with('upload', $icon['error']);
            }
        }else{
            return redirect()->back()->withInput()->with('upload', '请上传图片');
        }

        DB::table('cfg_menu')->insert([
            'menu_name'=>$menu_name,
            'm_url'=>$m_url,
            'icon'=>$icon['url'],
            'dtype'=>1,
            'isinner'=>6,
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
        $where[]=['menu_name','=',$menu_name];
        $where[]=['id','!=',$id];
        $count = DB::table('cfg_menu')->where($where)->count();
        if (!empty($count)) {
            return redirect()->back()->withInput()->with('success', '存在相同名称');
        }
        $m_url=$request->m_url;
        //$icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        if ($request->hasFile('icon')) {
            $icon=UploadTool::UploadImgForm($request,'icon');
            if (isset($icon['error'])){
                return redirect()->back()->with('upload', $icon['error']);
            }
        }
        $update=['menu_name'=>$menu_name, 'm_url'=>$m_url];
        if (!empty($icon)){
            $update['icon']=$icon['url'];
        }
        DB::table('cfg_menu')->where('id',$id)->update($update);

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
