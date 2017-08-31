<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 优惠头条优化原因设置
 * Class DiscountHeadlinesOptimizationReasonsController
 * @package App\Http\Controllers\Admin\Ks
 */
class DiscountHeadlinesOptimizationReasonsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //条件
        $infos = DB::table('cfg_pr_reducereason')->paginate(10);

        return view('admin.ks.dhor.index', ['infos' => $infos, 'page_size' => 10]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ks.dhor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $r_name = $request->r_name;
        $count=DB::table('cfg_pr_reducereason')->where(['r_name'=>$r_name])->count();
        if (!empty($count)){
            return redirect()->back()->with('success', '不能重名');
        }
        DB::table('cfg_pr_reducereason')->insert(['r_name' => $r_name]);

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
        return view('admin.ks.user_info.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = DB::table('cfg_pr_reducereason')->where('r_id', $id)->first();

        return view('admin.ks.dhor.create', compact('info'));
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
        $r_name = $request->r_name;
        $count=DB::table('cfg_pr_reducereason')->where(['r_name'=>$r_name])->whereNotIn('r_id',[$id])->count();
        if (!empty($count)){
            return redirect()->back()->with('success', '不能重名');
        }
        DB::table('cfg_pr_reducereason')->where('r_id', $id)->update([
            'r_name' => $r_name
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
        $count=DB::table('user_prcate_reducereason')->where('rid',$id)->count();
        if($count){
            return response()->json([
                'msg' => -1,'info'=>'已经被使用无法删除'
            ]);
        }
        DB::table('cfg_pr_reducereason')->where('r_id', $id)->delete();
        return response()->json([
            'msg' => 1
        ]);
    }

    function batch_destroy(Request $request)
    {

        $ids = $request->ids;
        $count=DB::table('user_prcate_reducereason')->whereIn('rid',$ids)->count();
        if($count){
            return response()->json([
                'msg' => -1,'info'=>'已经被使用无法删除'
            ]);
        }
        DB::table('cfg_pr_reducereason')->whereIn('r_id', $ids)->delete();
        return response()->json([
            'msg' => 1
        ]);


    }

}
