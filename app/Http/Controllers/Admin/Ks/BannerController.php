<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\UploadTool;

/**
 * 轮播图管理
 * Class BannerController
 * @package App\Http\Controllers\Admin\Ks
 */
class BannerController extends BaseController
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
            $where[] = ['title', 'like', '%' . $where_str . '%'];

        }

        //条件
        $infos = DB::table('cfg_banner')->where($where)->paginate($this->page_size);

        return view('admin.ks.banner.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //合作机会
        $jh = DB::table('cooperation_opportunity')->select(['id','title'])->get();
        $tt = DB::table('headline_info')->select(['hid','title'])->get();
        $cj = DB::table('user')->select(['uid','company'])->get();
        return view('admin.ks.banner.create',compact('jh','tt','cj'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url=UploadTool::UploadImg($request,'url','public/upload/img');
        if (empty($url)){
            return redirect()->back()->with('upload', '请上传轮播图');
        }
        $title=$request->title;
        $type=$request->type;
        $r_url=$request->r_url;
        if(empty($r_url)){
            switch ($type){
                case 2:
                    //厂家
                    return redirect()->back()->with('success', '请选择厂家/商家主页');
                    break;
                case 3:
                    //头条
                    return redirect()->back()->with('success', '请选择优惠头条');
                    break;
                case 4:
                    //机会
                    return redirect()->back()->with('success', '请选择合作机会');
                    break;
                default:
                    return redirect()->back()->with('success', '请输入网址');

            }
        }
        DB::table('cfg_banner')->insert([
            'title'=>$title,
            'type'=>$type,
            'r_url'=>$r_url,
            'url'=>$url,
        ]);
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
        $info=DB::table('cfg_banner')->where('id',$id)->first();
        return view('admin.ks.banner.create',compact('info'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jh = DB::table('cooperation_opportunity')->select(['id','title'])->get();
        $tt = DB::table('headline_info')->select(['hid','title'])->get();
        $cj = DB::table('user')->select(['uid','company'])->get();
        $info=DB::table('cfg_banner')->where('id',$id)->first();
        return view('admin.ks.banner.create',compact('info','jh','tt','cj'));
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
        $url=UploadTool::UploadImg($request,'url','public/upload/img');
        $title=$request->title;
        $type=$request->type;
        $r_url=$request->r_url;
        $update=[
            'title'=>$title,
            'type'=>$type,
            'r_url'=>$r_url,
        ];
        if (!empty($url)){
            $update['url']=$url;
        }
        DB::table('cfg_banner')->where('id',$id)->update($update);
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
        DB::table('cfg_banner')->where('id',$id)->delete();
        return response()->json([
            'msg' => 1
        ]);
    }
}
