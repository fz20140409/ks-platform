<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * 优质商家
 * Class QualityMerchantsController
 * @package App\Http\Controllers\Admin\Ks
 */
class QualityMerchantsController extends BaseController
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
        $provices=DB::table('user')->select('provice')->distinct('provice')->get();
        $str='';
        if (isset($where_str)) {
            $str.=" and d.company like '%$where_str%'";

        }

        $infos= DB::table(DB::raw("(SELECT a.mid,c.company,c.provice FROM `great_merchant` AS a LEFT JOIN merchant AS b ON a.mid=b.sr_id LEFT JOIN `user` AS c ON b.uid=c.uid WHERE b.mtype IN (4,5,6)) as d where 1=1 $str"))->paginate($this->page_size);
        return view('admin.ks.qum.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,'provices'=>$provices,'where_str' => $where_str]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $where_str = $request->where_str;
        $provices=DB::table('user')->select('provice')->distinct('provice')->get();
        $str='';
        if (isset($where_str)) {
            $str.=" and d.company like '%$where_str%'";

        }

        $infos= DB::table(DB::raw("(SELECT a.mid,c.company,c.provice FROM `great_merchant` AS a LEFT JOIN merchant AS b ON a.mid=b.sr_id LEFT JOIN `user` AS c ON b.uid=c.uid WHERE b.mtype IN (4,5,6)) as d where 1=1 $str"))->paginate($this->page_size);
        return view('admin.ks.qum.create',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,'provices'=>$provices,'where_str' => $where_str]);

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
