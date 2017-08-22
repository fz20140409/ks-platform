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
        $area = isset($request->area)?$request->area:-1;
        $type = isset($request->type)?$request->type:-1;
        $is_auth = isset($request->is_auth)?$request->is_auth:-1;
        $where_link['page_size'] =$this->page_size ;
        $where = array();
        if (isset($where_str)) {
            $where[] = ['b.phone', 'like', '%' . $where_str . '%'];
            $where_link['where_str']=$where_str;
        }
        if ($area!=-1){
            $where[] = ['b.provice', '=', $area];
            $where_link['area']=$area;
        }
        if ($type!=-1){
            $where[] = ['c.type_name', '=', $type];
            $where_link['type']=$type;
        }
        if ($is_auth!=-1){
            $where[] = ['a.iscertifi', '=', $is_auth];
            $where_link['is_auth']=$is_auth;
        }

        $provices=DB::select("SELECT DISTINCT provice FROM `user`");
        $types=DB::select("SELECT DISTINCT a.type_name FROM `merchant` LEFT JOIN user_type_info AS a ON mtype=a.id");

        //条件
        $infos=DB::table('merchant As a')->select('a.sr_id','b.phone','b.provice','c.type_name','b.company','a.iscertifi','a.honesty',
            DB::raw("(SELECT COUNT('uid') FROM user_merchant_favor WHERE sr_id=a.sr_id) AS favor"), DB::raw("(SELECT COUNT(*) FROM goods WHERE sr_id=a.sr_id) AS goods_num"),
            DB::raw("(SELECT COUNT(*) FROM great_merchant WHERE mid=a.sr_id) AS is_yz"))->leftJoin('user as b','a.uid','=','b.uid')->leftJoin('user_type_info AS c','a.mtype','=','c.id')->where($where)->paginate($this->page_size);

       return view('admin.ks.user_info.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,
           'where_str' => $where_str,'provices'=>$provices,'types'=>$types,
           'area'=>$area,'type'=>$type,'is_auth'=>$is_auth,
           'where_link'=>$where_link

           ]);

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
        $info=DB::select("SELECT b.uicon,b.company,c.type_name,a.iscertifi,a.honesty,(SELECT COUNT('uid') FROM user_merchant_favor WHERE sr_id = a.sr_id) AS favor,(SELECT COUNT(*) FROM great_merchant WHERE mid = a.sr_id) AS is_yz,(
		SELECT
			COUNT(*)
		FROM
			goods
		WHERE
			sr_id = a.sr_id
	) AS goods_num
FROM `merchant` AS a
LEFT JOIN `user` AS b ON a.uid=b.uid
LEFT JOIN user_type_info AS c ON a.mtype=c.id
WHERE a.sr_id=$id")[0];

        return view('admin.ks.user_info.create',compact('info'));

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
