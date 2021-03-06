<?php

namespace App\Http\Controllers\Admin\Ks;

use Session;
use URL;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**渠道设置
 * Class SalechanelController
 * @package App\Http\Controllers\Admin\Ks
 */
class SalechanelController extends BaseController
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
            $where[] = ['sale_name', 'like', '%' . $where_str . '%'];

        }

        //条件
        $infos=DB::table('cfg_salechanel')->select(['sale_name','sid'])->where($where)->paginate($this->page_size);

        return view('admin.ks.salechanel.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,'where_str' => $where_str]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //
        return view('admin.ks.salechanel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $sale_name=$request->sale_name;
        if(empty($sale_name)){
            return response()->json(['msg'=>'渠道名称不能为空']);
        }
        if(mb_strlen($sale_name) > 35){
            return response()->json(['msg'=>'渠道名称不能超过35字符']);
        }

        $pid=$request->pid;
        $where=array('sale_name'=>$sale_name);
        if(isset($pid)){
            $where['parent_id']=$pid;
        }else{
            $pid=0;
        }

        $count= DB::table('cfg_salechanel')->where($where)->where(['enabled'=>1])->count();
        if(!empty($count)){
            return response()->json(['msg'=>'渠道名称不允许重名']);
        }

        $zylevel = DB::table('cfg_salechanel')->where('sid', $pid)->value('zylevel');
        $insert=[
            'sale_name'=>$sale_name,
            'parent_id'=>$pid,
            'zylevel' => intval($zylevel) + 1,
            'createtime'=>date('Y-m-d H:i:s',time())
        ];
        
        if( DB::table('cfg_salechanel')->insert($insert)){
            return response()->json(['msg'=>1]);
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
    public function edit($id)
    {
        //
        $info = DB::table('cfg_salechanel')->where('sid',$id)->first();
        $info->url=route('admin.ks.salechanel.update_post',$id);
        return response()->json($info);
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
        $sale_name=$request->sale_name;
        $where=array();
        $where[]=['sale_name','=',$sale_name];
        $where[]=['sid','!=',$id];
        $count= DB::table('cfg_salechanel')->where($where)->where(['enabled'=>1])->count();
        if (!empty($count)) {
            return response()->json(['msg'=>'渠道名称不允许重名']);
        }

        DB::table('cfg_salechanel')->where('sid',$id)->update(['sale_name' => $sale_name]);
        return response()->json(['msg'=>1]);

    }
    //修改update请求方式
    function update_post(Request $request, $id){
        $sale_name=$request->sale_name;
        $where=array();
        $where[]=['sale_name','=',$sale_name];
        $where[]=['sid','!=',$id];
        $count= DB::table('cfg_salechanel')->where($where)->where(['enabled'=>1])->count();
        if (!empty($count)) {
            return response()->json(['msg'=>'渠道名称不允许重名']);
        }

        DB::table('cfg_salechanel')->where('sid',$id)->update(['sale_name' => $sale_name,'updatetime'=>date('Y-m-d H:i:s',time())]);
        return response()->json(['msg'=>1]);

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
            $infos=DB::table('cfg_salechanel')->where('parent_id',$id)->select('sid')->get()->toArray();
            $ids=array();
            foreach ($infos as $info){
                $ids[]=$info->sid;
            }
            DB::table('cfg_salechanel')->whereIn('parent_id', $ids)->update(['enabled'=>0]);
            DB::table('cfg_salechanel')->whereIn('sid', $ids)->update(['enabled'=>0]);
            DB::table('cfg_salechanel')->where('sid', $id)->update(['enabled'=>0]);

            return response()->json(['msg' => 1]);
        }
        $count=DB::table('cfg_salechanel')->where('parent_id',$id)->where('enabled',1)->count();
        if(!empty($count)){
            return response()->json(['msg'=>'该分类下有子分类，是否一起删除?']);
        }

        DB::table('cfg_salechanel')->where('sid', $id)->update(['enabled'=>0]);
        return response()->json(['msg' => 1]);


    }
    function batch_destroy(Request $request){
        $ids = $request->ids;

        //确认删除子分类
        if(isset($request->flag)){
            //当前分类下，子分类
            $infos=DB::table('cfg_salechanel')->whereIn('parent_id',$ids)->select('sid')->get()->toArray();
            $idss=array();
            foreach ($infos as $info){
                $idss[]=$info->sid;
            }
            DB::table('cfg_salechanel')->whereIn('parent_id', $idss)->delete();//子分类的子分类
            DB::table('cfg_salechanel')->whereIn('sid', $idss)->delete();//子分类
            DB::table('cfg_salechanel')->whereIn('sid', $ids)->delete();//当前分类

            return response()->json(['msg' => 1]);
        }
        $count=DB::table('cfg_salechanel')->whereIn('parent_id',$ids)->count();
        if(!empty($count)){
            return response()->json(['msg'=>'该分类下有子分类，是否一起删除?']);
        }

        DB::table('cfg_salechanel')->whereIn('sid', $ids)->delete();
        return response()->json(['msg' => 1]);

    }

    /**
     * 展示子分类
     */
    function showSub(Request $request,$id){
        $where_str = $request->where_str;
        $where = array();

        $where[]=['parent_id','=',$id];
        if (isset($where_str)) {
            $where[] = ['sale_name', 'like', '%' . $where_str . '%'];

        }

        if ($request->level == 2) {
            Session::put('salechanel_id', $id);
            $previous = route('admin.ks.salechanel.index');
            $parent = DB::table('cfg_salechanel')->where('sid', $id)->select('sale_name')->get()[0]->sale_name;
        }
        if ($request->level == 3) {
            $previous = route('admin.ks.salechanel.showSub', ['id' => Session::get('salechanel_id'), 'level' => 2]);
            $name1 = DB::table('cfg_salechanel')->where('sid', $id)->select('sale_name')->get()[0];
            $name2 = DB::select("SELECT b.sale_name FROM `cfg_salechanel` AS a LEFT JOIN cfg_salechanel AS b ON a.parent_id=b.sid WHERE a.sid=$id")[0];
            $parent = $name2->sale_name . "-->" . $name1->sale_name;
        }

        //条件
        $infos=DB::table('cfg_salechanel')->select(['sale_name','sid'])->where($where)->where(['enabled'=>1])->paginate($this->page_size);

        $data = array(
            'infos'=>$infos,
            'page_size' => $this->page_size,
            'page_sizes' => $this->page_sizes,
            'where_str' => $where_str,
            'level'=>$request->level,
            'pid'=>$id,
            'parent' => $parent,
            'previous' => $previous
        );

        return view('admin.ks.salechanel.index', $data);
    }
}
