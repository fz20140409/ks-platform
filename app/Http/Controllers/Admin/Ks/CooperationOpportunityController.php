<?php

namespace App\Http\Controllers\Admin\Ks;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\DB;

/**
 * 合作机会
 * Class CooperationOpportunityController
 * @package App\Http\Controllers\Admin\Ks
 */
class CooperationOpportunityController extends BaseController
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
        $infos=DB::table('merchant As a')->select('a.sr_id','b.phone','b.provice','c.type_name','b.company','a.iscertifi','a.honesty',
            DB::raw("(SELECT COUNT('uid') FROM user_merchant_favor WHERE sr_id=a.sr_id) AS favor"), DB::raw("(SELECT COUNT(*) FROM goods WHERE sr_id=a.sr_id) AS goods_num"),
            DB::raw("(SELECT COUNT(*) FROM great_merchant WHERE mid=a.sr_id) AS is_yz"))->leftJoin('user as b','a.uid','=','b.uid')->leftJoin('user_type_info AS c','a.mtype','=','c.id')->paginate($this->page_size);

        return view('admin.ks.coop.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,'where_str' => $where_str]);

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
