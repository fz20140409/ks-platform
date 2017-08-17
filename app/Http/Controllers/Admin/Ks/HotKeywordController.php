<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 热搜关键字
 * Class HotKeywordController
 * @package App\Http\Controllers\Admin\Ks
 */
class HotKeywordController extends BaseController
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
        $is_recommend = isset($request->is_recommend) ? $request->is_recommend : -1;
        $where = array(['enabled', '=', 1]);

        if (isset($where_str)) {
            $where[] = ['searchname', 'like', '%' . $where_str . '%'];

        }
        if ($is_recommend != -1) {
            $where[] = ['is_recommend', '=', $is_recommend];
        }

        //条件
        $infos = DB::table('cfg_hot_search')->where($where)->orderBy('search_count', 'desc')->paginate($this->page_size);

        return view('admin.ks.hk.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str, 'is_recommend' => $is_recommend]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.ks.hk.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $searchname = $request->searchname;
        $count = DB::table('cfg_hot_search')->where('searchname',$searchname)->count();
        if (!empty($count)) {
            return redirect()->back()->withInput()->with('success', '存在相同关键字');
        }
        $is_recommend = $request->is_recommend;
        DB::table('cfg_hot_search')->insert([
            'searchname' => $searchname,
            'is_recommend' => $is_recommend,
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
        $info = DB::table('cfg_hot_search')->where('id', $id)->first();
        return view('admin.ks.hk.create', compact('info'));
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
        //
        $searchname = $request->searchname;
        $where[]=['searchname','=',$searchname];
        $where[]=['id','!=',$id];
        $count = DB::table('cfg_hot_search')->where($where)->count();
        if (!empty($count)) {
            return redirect()->back()->withInput()->with('success', '存在相同关键字');
        }
        $is_recommend = $request->is_recommend;
        DB::table('cfg_hot_search')->where('id', $id)->update([
            'searchname' => $searchname,
            'is_recommend' => $is_recommend,
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
        DB::table('cfg_hot_search')->where('id', $id)->update([
            'enabled' => 0
        ]);
        return response()->json([
            'msg' => 1
        ]);

    }

    function updateStatus($id)
    {
        $info = DB::table('cfg_hot_search')->where('id', $id)->first();
        if ($info->is_recommend == 1) {
            DB::table('cfg_hot_search')->where('id', $id)->update([
                'is_recommend' => 0
            ]);
        } else {
            DB::table('cfg_hot_search')->where('id', $id)->update([
                'is_recommend' => 1
            ]);
        }
        return response()->json([
            'msg' => 1
        ]);

    }

    public function batch_destroy(Request $request)
    {
        $ids = $request->ids;
        DB::table('cfg_hot_search')->whereIn('id', $ids)->update([
            'enabled' => 0
        ]);
        return response()->json([
            'msg' => 1
        ]);

    }
}
