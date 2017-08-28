<?php

namespace App\Http\Controllers\Admin\ks;

use Auth;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**APP版本管理
 * Class AppVersionController
 * @package App\Http\Controllers\Admin\Ks
 */
class AppVersionController extends BaseController
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

        if (isset($where_str)) {
            $where[] = ['version', 'like', '%' . $where_str . '%'];
        }

        //条件
        $infos=DB::table('cfg_app_version')->select(['id', 'download_type', 'version', 'terminal_type', 'url', 'update_info', 'create_time', 'update_time', 'operator_name'])->where($where)->paginate($this->page_size);


        $downloadType = $this->getDownloadType();
        $terminalType = $this->getTerminalTye();
        foreach ($infos as $value) {
            $value->download_type = $downloadType[$value->download_type];
            $value->terminal_type = $terminalType[$value->terminal_type];
        }

        $data = array(
            'infos'=>$infos,
            'page_size' => $this->page_size,
            'page_sizes' => $this->page_sizes,
            'where_str' => $where_str,
            'download_type' => $downloadType,
            'terminal_type' => $terminalType
        );

        return view('admin.ks.av.index', $data);
    }

    // 下载类型
    public function getDownloadType()
    {
        return array('1' => '非强制下载','2' => '强制下载');
    }

    // 终端类型
    public function getTerminalTye()
    {
        return array('1' => '安卓', '2' => 'ios');
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
        if(empty($input['download_type'])){
            return response()->json(['msg'=>'下载类型不能为空']);
        }
        if(empty($input['version'])){
            return response()->json(['msg'=>'版本号不能为空']);
        }
        if(empty($input['terminal_type'])){
            return response()->json(['msg'=>'终端类型不能为空']);
        }
        if(empty($input['url'])){
            return response()->json(['msg'=>'下载链接不能为空']);
        }
        if(empty($input['update_info'])){
            return response()->json(['msg'=>'更新信息不能为空']);
        }

        $version = DB::table('cfg_app_version')->where(['version' => $input['version']])->first();
        if(!empty($version)){
            return response()->json(['msg'=>'存在相同版本号']);
        }

        $insert = array(
            'download_type' => $input['download_type'],
            'version' => $input['version'],
            'terminal_type' => $input['terminal_type'],
            'url' => $input['url'],
            'update_info' => $input['update_info'],
            'create_time' => date('Y-m-d H:i:s'),
            'operator_user_id' => Auth::user()->id,
            'operator_name' => Auth::user()->name
        );


        if( DB::table('cfg_app_version')->insert($insert)){
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
        $info = DB::table('cfg_app_version')->where('id', $id)->first();
        $info->link = route('admin.ks.av.update_post', $id);
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
        if(empty($input['download_type'])){
            return response()->json(['msg'=>'下载类型不能为空']);
        }
        if(empty($input['version'])){
            return response()->json(['msg'=>'版本号不能为空']);
        }
        if(empty($input['terminal_type'])){
            return response()->json(['msg'=>'终端类型不能为空']);
        }
        if(empty($input['url'])){
            return response()->json(['msg'=>'下载链接不能为空']);
        }
        if(empty($input['update_info'])){
            return response()->json(['msg'=>'更新信息不能为空']);
        }

        $where = array();
        $where[] = ['version', '=', $input['version']];
        $where[] = ['id','!=', $id];

        $version = DB::table('cfg_app_version')->where($where)->first();
        if(!empty($version)){
            return response()->json(['msg'=>'存在相同版本号']);
        }

        $insert = array(
            'download_type' => $input['download_type'],
            'version' => $input['version'],
            'terminal_type' => $input['terminal_type'],
            'url' => $input['url'],
            'update_info' => $input['update_info'],
            'update_time' => date('Y-m-d H:i:s'),
            'operator_user_id' => Auth::user()->id,
            'operator_name' => Auth::user()->name
        );


        if( DB::table('cfg_app_version')->where('id', $id)->update($insert) ){
            return response()->json(['msg'=>1]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( DB::table('cfg_app_version')->where('id', $id)->delete() )
        return response()->json(['msg' => 1]);
    }

    /**
     * 批量删除
     */
    function batch_destroy(Request $request){
        $ids = $request->ids;
        DB::table('cfg_app_version')->whereIn('id', $ids)->delete();
        return response()->json(['msg' => 1]);

    }
}
