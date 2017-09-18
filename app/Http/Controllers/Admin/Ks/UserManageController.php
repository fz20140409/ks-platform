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
class UserManageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where_str = $request->where_str;
        //使用状态
        $enabled = isset($request->enabled)?$request->enabled:-1;
        //用户类型
        $type = isset($request->type)?$request->type:-1;
        //认证
        $is_auth = isset($request->is_auth)?$request->is_auth:-1;
        $where_link['page_size'] =$this->page_size ;
        $where=[];
        $orWhere=[];
        if (isset($where_str)) {
            $where[] = ['phone', 'like', '%' . $where_str . '%'];
            $orWhere[] = ['username', 'like', '%' . $where_str . '%'];
            $orWhere[] = ['IDname', 'like', '%' . $where_str . '%'];
            $where_link['where_str']=$where_str;
        }
        if ($enabled!=-1){
            $where[] = ['enabled', '=', $enabled];
            $where_link['enabled']=$enabled;
        }
        if ($type!=-1){
            $where[] = ['utype', '=', $type];
            $where_link['type']=$type;
        }
        if ($is_auth!=-1){
            $where[] = ['iscertifi', '=', $is_auth];
            $where_link['is_auth']=$is_auth;
        }


        $types=DB::select("SELECT * from user_type_info");
        $type_arr=[];
        foreach ($types as $item){
            $type_arr[$item->id]=$item->type_name;
        }

        //条件
        $infos=DB::table('user')->where($where)->orWhere($orWhere)->paginate($this->page_size);

        return view('admin.ks.um.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,
            'where_str' => $where_str,'types'=>$types,
            'enabled'=>$enabled,'type'=>$type,'is_auth'=>$is_auth,
            'where_link'=>$where_link,'type_arr'=>$type_arr

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
        $info=DB::table('user as a')->select('a.*','b.type_name')->leftJoin('user_type_info as b','a.utype','=','b.id')->where('a.uid',$id)->first();

        return view('admin.ks.um.create',compact('info'));

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
    //个人信息
    function getPersonInfo($id){
        $info=DB::table('user as a')->select('a.*','b.type_name')->leftJoin('user_type_info as b','a.utype','=','b.id')->where('a.uid',$id)->first();
        $honesty = DB::table('merchant')->where('uid', $id)->value('honesty');

        return view('admin.ks.um.person_info',compact('info', 'honesty'));

    }
    //老板信息
    function getBossInfo($id){
        return view('admin.ks.um.boss_info',compact('info'));
    }
    //业务信息
    function getBusinessInfo($id){
        $s = DB::table('merchant')->where('uid', $id)->value('honesty');
        return view('admin.ks.um.business_info',compact('info'));
    }
    //经营人
    function getTransactorInfo($id){
        return view('admin.ks.um.transactor_info',compact('info'));

    }
    //企业信息
    function getCompanyInfo($id){
        // 用户信息
        $user = DB::table('user')->where('uid', $id)->first();
        // 商户信息
        $merchant = DB::table('merchant')->where('uid', $id)->first();
        // 营业执照
        $merchant_file = DB::table('merchant_file')->where('sr_id', $merchant->sr_id)->where('filetype', 2)->where('enabled', 1)->get();
        // 业务辐射区
        $merchant_dealers_ywfs = DB::table('merchant_dealers_ywfs as md')
                                    ->select('cl.name')
                                    ->leftJoin('cfg_locations as cl', 'md.bizarea_id', '=', 'cl.id')
                                    ->where('md.sr_id', $merchant->sr_id)
                                    ->orderBy('cl.parent_id', 'asc')
                                    ->get()
                                    ->toArray();
        $merchant_dealers_ywfs = array_map('get_object_vars', $merchant_dealers_ywfs);

        $data = compact('user', 'merchant', 'merchant_file', 'merchant_dealers_ywfs');
        return view('admin.ks.um.company_info', $data);
    }
}
