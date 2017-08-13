<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 优惠头条分类
 * Class DiscountHeadlinesCategoryController
 * @package App\Http\Controllers\Admin\Ks
 */
class DiscountHeadlinesCategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //条件
        $infos = DB::table('cfg_preferential_cate')->paginate(10);

        return view('admin.ks.dhc.index', ['infos' => $infos, 'page_size' => 10]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ks.dhc.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $catename = $request->catename;
        $count=DB::table('cfg_preferential_cate')->where('catename',$catename)->count();
        if(!empty($count)){
            return redirect()->back()->with('success', '存在相同分类名称');
        }
        DB::table('cfg_preferential_cate')->insert(['catename' => $catename]);

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
        $info = DB::table('cfg_preferential_cate')->where('id', $id)->first();

        return view('admin.ks.dhc.create', compact('info'));
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
        $catename = $request->catename;
        $count=DB::table('cfg_preferential_cate')->where('catename',$catename)->whereNotIn('id',[$id])->count();
        if(!empty($count)){
            return redirect()->back()->with('success', '存在相同分类名称');
        }
        DB::table('cfg_preferential_cate')->where('id', $id)->update([
            'catename' => $catename
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
        DB::table('cfg_preferential_cate')->where('id', $id)->delete();
        return response()->json([
            'msg' => 1
        ]);
    }

    function batch_destroy(Request $request)
    {
        $ids = $request->ids;
        DB::table('cfg_preferential_cate')->whereIn('id', $ids)->delete();
        return response()->json([
            'msg' => 1
        ]);


    }

}
