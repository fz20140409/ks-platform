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
        $where = array();

        if (isset($where_str)) {
            $where[] = ['area', 'like', '%' . $where_str . '%'];
        }

        //条件
        $infos=DB::table('merchant_kf_rela')->select(['id', 'area', 'phone'])->where($where)->paginate($this->page_size);

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
        $input['phone'] = trim($input['phone']);

        if(empty($input['area']) || mb_strlen(trim($input['area'])) > 6){
            return response()->json(['msg'=>'区域名称不能为空并且不能大于6个汉字']);
        }
        if(empty($input['phone'])){
            return response()->json(['msg'=>'电话不能为空']);
        }

        $isMob="/^1[3-8]{1}[0-9]{9}$/";
        $isTel="/^([0-9]{3,4}-)?[0-9]{7,8}$/";

        if(!preg_match($isMob, $input['phone']) && !preg_match($isTel, $input['phone'])) {
            return response()->json(['msg'=>'请输入有效电话号码']);
        }

        $kf_rela = DB::table('merchant_kf_rela')->where('area', $input['area'])->first();
        if ($kf_rela) {
            return response()->json(['msg'=>'区域名称不允许重名']);
        }

        $insert = array(
            'area' => trim($input['area']),
            'phone' => trim($input['phone'])
        );

        if( DB::table('merchant_kf_rela')->insert($insert)){
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
        $info = DB::table('merchant_kf_rela')->where('id', $id)->first();
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
        $input['phone'] = trim($input['phone']);

        if(empty($input['area']) || mb_strlen(trim($input['area'])) > 6){
            return response()->json(['msg'=>'区域名称不能为空并且不能大于6个汉字']);
        }
        if(empty($input['phone'])){
            return response()->json(['msg'=>'电话不能为空']);
        }

        $isMob="/^1[3-8]{1}[0-9]{9}$/";
        $isTel="/^([0-9]{3,4}-)?[0-9]{7,8}$/";

        if(!preg_match($isMob, $input['phone']) && !preg_match($isTel, $input['phone'])) {
            return response()->json(['msg'=>'请输入有效电话号码']);
        }

        $where=array();
        $where[]=['area', '=', $input['area']];
        $where[]=['id', '!=', $id];
        $kf_rela = DB::table('merchant_kf_rela')->where($where)->first();
        if ($kf_rela) {
            return response()->json(['msg'=>'区域名称不允许重名']);
        }

        $data = array(
            'area' => $input['area'],
            'phone' => $input['phone']
        );

        DB::table('merchant_kf_rela')->where('id', $id)->update($data);
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
        DB::table('merchant_kf_rela')->where('id', $id)->delete();
        return response()->json(['msg' => 1]);
    }

    /**
     * 批量删除
     */
    function batch_destroy(Request $request){
        $ids = $request->ids;
        DB::table('merchant_kf_rela')->whereIn('id', $ids)->delete();
        return response()->json(['msg' => 1]);

    }
}
