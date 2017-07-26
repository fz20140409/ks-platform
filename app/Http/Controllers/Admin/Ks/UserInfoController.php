<?php

namespace App\Http\Controllers\Admin\Ks;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\DB;

/**
 * 网店列表
 * Class KsUserInfoController
 * @package App\Http\Controllers\Admin
 */
class UserInfoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where_str = $request->where_str;
        $where = array();
        $orWhere = array();
        if (isset($where_str)) {
            $where[] = ['a.phone', 'like', '%' . $where_str . '%'];
            $orWhere[] = ['a.provice', 'like', '%' . $where_str . '%'];
        }

        //条件
        $infos=DB::table('user as a')->leftJoin('user_type_info as b','a.utype','=','b.id')->select(['a.uid','a.phone','a.provice','b.type_name','a.company','a.iscertifi'])->where($where)->orWhere($orWhere)->paginate($this->page_size);

       return view('admin.ks.user_info.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,'where_str' => $where_str]);

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        return view('admin.ks.user_info.create');

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
        //
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
    }
}
