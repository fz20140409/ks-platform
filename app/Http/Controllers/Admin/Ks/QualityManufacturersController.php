<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * 优质厂家
 * Class QualityMerchantsController
 * @package App\Http\Controllers\Admin\Ks
 */
class QualityManufacturersController extends BaseController
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
        $provice = isset($request->provice)?$request->provice:-1;
        $provices = DB::table('user')->select('provice')->distinct('provice')->get();
        $link_where=array();//分页条件数组
        $link_where['page_size']=$this->page_size;
        $str = '';
        if (isset($where_str)) {
            $link_where['where_str']="$where_str";
            $str .= " and d.company like '%$where_str%'";

        }
        if ($provice!=-1){
            $link_where['provice']="$provice";
            $str .= " and d.provice = '$provice'";
        }

        $infos = DB::table(DB::raw("(SELECT a.mid,c.company,c.provice FROM `great_merchant` AS a LEFT JOIN merchant AS b ON a.mid=b.sr_id LEFT JOIN `user` AS c ON b.uid=c.uid WHERE b.mtype IN (7,8)) as d where 1=1 $str"))->paginate($this->page_size);
        return view('admin.ks.qm.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'provices' => $provices, 'where_str' => $where_str,'provice'=>$provice,'link_where'=>$link_where]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $where_str = $request->where_str;
        $provice = isset($request->provice)?$request->provice:-1;
        $provices = DB::table('user')->select('provice')->distinct('provice')->get();
        $where=array();
        $link_where=array();//分页条件数组
        $link_where['page_size']=$this->page_size;
        if (isset($where_str)) {
            $link_where['where_str']="$where_str";
            $where[] = ['f.company','LIKE',"%$where_str%"];

        }
        if ($provice!=-1){
            $link_where['provice']="$provice";
            $where[] = ['f.provice','=',"$provice"];
        }
        $infos = DB::table('merchant as d')->select('d.sr_id as mid','f.provice','f.company')->whereNotIn('d.sr_id', function ($query) {
            $query->select('a.mid')->from('great_merchant AS a')->leftJoin('merchant AS b', 'a.mid', '=', 'b.sr_id')->whereIn('b.mtype', [7,8]);
        })->leftJoin('user as f','d.uid','=','f.uid')->whereIn('d.mtype', [7,8])->where($where)->paginate($this->page_size);
        return view('admin.ks.qm.create', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'provices' => $provices, 'where_str' => $where_str,'provice'=>$provice,'link_where'=>$link_where]);

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
        $info=DB::table('merchant As a')->select('b.uicon','a.sr_id','b.phone','b.provice','c.type_name','b.company','a.iscertifi','a.honesty',
            DB::raw("(SELECT COUNT('uid') FROM user_merchant_favor WHERE sr_id=a.sr_id) AS favor"), DB::raw("(SELECT COUNT(*) FROM goods WHERE sr_id=a.sr_id) AS goods_num"),
            DB::raw("(SELECT COUNT(*) FROM great_merchant WHERE mid=a.sr_id) AS is_yz"))->leftJoin('user as b','a.uid','=','b.uid')->leftJoin('user_type_info AS c','a.mtype','=','c.id')->where('a.sr_id',$id)->first();
        return view('admin.ks.qm.show',compact('info'));

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
        DB::table('great_merchant')->where('mid',$id)->delete();
        return response()->json([
            'msg' => 1
        ]);
    }
    //添加优质厂家
    function add_qum($id){
        DB::table('great_merchant')->insert([
            'mid'=>$id,
            'create_time'=>date('Y-m-d H:i:s',time()),
        ]);

        return response()->json([
            'msg' => 1
        ]);


    }
    public function batch_destroy(Request $request)
    {
        $ids = $request->ids;
        DB::table('great_merchant')->whereIn('mid',$ids)->delete();
        return response()->json([
            'msg' => 1
        ]);

    }
    //批量添加优质厂家
    public function batch_add(Request $request)
    {
        $ids = $request->ids;
        $insert=[
            'create_time'=>date('Y-m-d H:i:s',time()),
        ];
        foreach ($ids as $id){
            $insert['mid']=$id;
            DB::table('great_merchant')->insert($insert);
        }
        return response()->json([
            'msg' => 1
        ]);

    }
}
