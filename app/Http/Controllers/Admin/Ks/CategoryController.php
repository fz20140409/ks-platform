<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Tools\UploadTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * 品类设置
 * Class CategoryController
 * @package App\Http\Controllers\Admin\Ks
 */
class CategoryController extends BaseController
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

        $where[]=['parent_id','=',0];
        $where[]=['enabled','=',1];
        if (isset($where_str)) {
            $where[] = ['cat_name', 'like', '%' . $where_str . '%'];

        }

        //条件
        $infos=DB::table('cfg_category')->select(['cat_name','cat_id','cat_icon'])->where($where)->paginate($this->page_size);

        return view('admin.ks.category.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,'where_str' => $where_str]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $pid=isset($request->pid)?$request->pid:0;
        $level=isset($request->level)?$request->level:1;
        return view('admin.ks.category.create',compact('pid','level'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        $cat_name=$request->cat_name;
        $pid=$request->pid;
        $where=array('cat_name'=>$cat_name);
        $where['parent_id']=$pid;
        $where['enabled']=1;
        //同名判断
        $count= DB::table('cfg_category')->where($where)->count();
        if(!empty($count)){
            return redirect()->back()->with('success', '存在相同品类名称');

        }
        $insert=[
            'cat_name'=>$cat_name,
            'cat_icon'=>$icon,
            'parent_id'=>$pid,
            'createtime'=>date('Y-m-d H:i:s',time()),

        ];


       if( DB::table('cfg_category')->insert($insert)){
           return redirect()->back()->with('success', '添加成功');
       }
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $pid=isset($request->pid)?$request->pid:0;
        $level=isset($request->level)?$request->level:1;
        $info = DB::table('cfg_category')->where('cat_id',$id)->first();
        $info->url=route('admin.ks.category.update',$id);
       return view('admin.ks.category.create',compact('info','pid','level'));
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
        $icon=UploadTool::UploadImg($request,'icon','public/upload/img');
        $cat_name=$request->cat_name;
        //同名判断
        $where=array();
        $where[]=['cat_name','=',$cat_name];
        $where[]=['cat_id','!=',$id];
        $where[]=['enabled','=',1];
        $count= DB::table('cfg_category')->where($where)->count();
        if (!empty($count)) {
            return redirect()->back()->with('success', '存在相同品类名称');
        }
        //更新的数据
        $update['cat_name']=$cat_name;
        $update['updatetime']=date('Y-m-d H:i:s',time());
        //有重新上传图片，才更新
        if (!empty($icon)){
            $update['cat_icon']=$icon;
        }

        DB::table('cfg_category')->where('cat_id',$id)->update($update);
        return redirect()->back()->with('success', '更新成功');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //确认删除子分类
        if(isset($request->flag)){
            //当前分类下，子分类
            $infos=DB::table('cfg_category')->where('parent_id',$id)->select('cat_id')->get()->toArray();
            $ids=array();
            foreach ($infos as $info){
                $ids[]=$info->cat_id;
            }
            DB::table('cfg_category')->whereIn('parent_id', $ids)->update([
                'enabled'=>0
            ]);//三级分类
            DB::table('cfg_category')->whereIn('cat_id', $ids)->update([
                'enabled'=>0
            ]);//二级分类
            DB::table('cfg_category')->where('cat_id', $id)->update([
                'enabled'=>0
            ]);//一级分类

            return response()->json(['msg' => 1]);
        }
        $count=DB::table('cfg_category')->where('parent_id',$id)->count();
        if(!empty($count)){
            return response()->json(['msg'=>'该分类下有子分类，是否一起删除?']);
        }

        DB::table('cfg_category')->where('cat_id', $id)->update([
            'enabled'=>0
        ]);
        return response()->json(['msg' => 1]);


    }
    function batch_destroy(Request $request){
        $ids = $request->ids;

        //确认删除子分类
        if(isset($request->flag)){
            //当前分类下，子分类
            $infos=DB::table('cfg_category')->whereIn('parent_id',$ids)->select('cat_id')->get()->toArray();
            $idss=array();
            foreach ($infos as $info){
                $idss[]=$info->cat_id;
            }
            DB::table('cfg_category')->whereIn('parent_id', $idss)->update([
                'enabled'=>0
            ]);//子分类的子分类
            DB::table('cfg_category')->whereIn('cat_id', $idss)->update([
                'enabled'=>0
            ]);//子分类
            DB::table('cfg_category')->whereIn('cat_id', $ids)->update([
                'enabled'=>0
            ]);//当前分类

            return response()->json(['msg' => 1]);
        }
        $count=DB::table('cfg_category')->whereIn('parent_id',$ids)->where('enabled',1)->count();
        if(!empty($count)){
            return response()->json(['msg'=>'该分类下有子分类，是否一起删除?']);
        }

        DB::table('cfg_category')->whereIn('cat_id', $ids)->update([
            'enabled'=>0
        ]);
        return response()->json(['msg' => 1]);

    }

    /**
     * 展示子分类
     */
    function showSub(Request $request,$id){
        $where_str = $request->where_str;
        $where = array();

        $where[]=['parent_id','=',$id];
        $where[]=['enabled','=',1];
        if (isset($where_str)) {
            $where[] = ['cat_name', 'like', '%' . $where_str . '%'];

        }
        $parent=DB::table('cfg_category')->where('cat_id',$id)->select('cat_name')->get()[0];


        //条件
        $infos=DB::table('cfg_category')->select(['cat_name','cat_id','cat_icon'])->where($where)->paginate($this->page_size);

        return view('admin.ks.category.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,'where_str' => $where_str,'level'=>$request->level,'pid'=>$id,'parent'=>$parent]);

    }
}
