<?php

namespace App\Http\Controllers\Admin\ks;

use Auth;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**平台客服电话设置
 * Class MerchantKfController
 * @package App\Http\Controllers\Admin\Ks
 */
class MerchantKfController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where_str = $request->where_str;

        $where['sr_id'] = 999999;
        $where['enabled'] = 1;
        if (isset($where_str)) {
            $where[] = ['area', 'like', '%' . $where_str . '%'];
        }

        //条件
        $infos=DB::table('cfg_kefu')->select(['id', 'area', 'tel'])->where($where)->paginate($this->page_size);

        $data = array(
            'infos'=>$infos,
            'page_size' => $this->page_size,
            'page_sizes' => $this->page_sizes,
            'where_str' => $where_str
        );

        return view('admin.ks.mk.index', $data);
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
        $input = $request->all();
        $input['area'] = trim($input['area']);
        $input['tel'] = trim($input['tel']);

        if(empty($input['area']) || mb_strlen(trim($input['area'])) > 6){
            return response()->json(['msg'=>'区域名称不能为空并且不能大于6个汉字']);
        }
        if(empty($input['tel'])){
            return response()->json(['msg'=>'电话不能为空']);
        }

        $isMob="/^1[3-8]{1}[0-9]{9}$/";
        $isTel="/^([0-9]{3,4}-)?[0-9]{7,8}$/";

        if(!preg_match($isMob, $input['tel']) && !preg_match($isTel, $input['tel'])) {
            return response()->json(['msg'=>'请输入有效电话号码']);
        }

        $kf_rela = DB::table('cfg_kefu')->where('sr_id', 999999)->where('area', $input['area'])->where('enabled', 1)->first();
        if ($kf_rela) {
            return response()->json(['msg'=>'区域名称不允许重名']);
        }

        $insert = array(
            'sr_id' => 999999,
            'area' => trim($input['area']),
            'tel' => trim($input['tel'])
        );

        if( DB::table('cfg_kefu')->insert($insert)){
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
        $info = DB::table('cfg_kefu')->where('id', $id)->first();
        $info->link = route('admin.ks.mk.update_post', $id);
        return response()->json($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_post(Request $request, $id)
    {
        $input = $request->all();
        $input['area'] = trim($input['area']);
        $input['tel'] = trim($input['tel']);

        if(empty($input['area']) || mb_strlen(trim($input['area'])) > 6){
            return response()->json(['msg'=>'区域名称不能为空并且不能大于6个汉字']);
        }
        if(empty($input['tel'])){
            return response()->json(['msg'=>'电话不能为空']);
        }

        $isMob="/^1[3-8]{1}[0-9]{9}$/";
        $isTel="/^([0-9]{3,4}-)?[0-9]{7,8}$/";

        if(!preg_match($isMob, $input['tel']) && !preg_match($isTel, $input['tel'])) {
            return response()->json(['msg'=>'请输入有效电话号码']);
        }

        $where=array();
        $where[]=['area', '=', $input['area']];
        $where[]=['sr_id', '=', 999999];
        $where[]=['id', '!=', $id];
        $where[]=['enabled', '=', 1];
        $kf_rela = DB::table('cfg_kefu')->where($where)->first();
        if ($kf_rela) {
            return response()->json(['msg'=>'区域名称不允许重名']);
        }

        $data = array(
            'area' => $input['area'],
            'tel' => $input['tel']
        );

        DB::table('cfg_kefu')->where('id', $id)->update($data);
        return response()->json(['msg'=>1]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('cfg_kefu')->where('id', $id)->delete();
        return response()->json(['msg' => 1]);
    }

    /**
     * 批量删除
     */
    function batch_destroy(Request $request){
        $ids = $request->ids;
        DB::table('cfg_kefu')->whereIn('id', $ids)->delete();
        return response()->json(['msg' => 1]);

    }
}
