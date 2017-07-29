<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $orWhere = array();
        if (isset($where_str)) {
            $where[] = ['a.phone', 'like', '%' . $where_str . '%'];
            $orWhere[] = ['a.provice', 'like', '%' . $where_str . '%'];
        }

        //条件
        $infos = DB::table('cfg_menu')->where($where)->orWhere($orWhere)->paginate($this->page_size);

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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    }
}
