<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 地区数据字典
 * Class LocationsController
 * @package App\Http\Controllers\Admin\Ks
 */
class LocationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $province = isset($request->province) ? $request->province : -1;
        $city = isset($request->city) ? $request->city : -1;
        $where_str = $request->where_str;
        $provinces = DB::table('cfg_locations')->where('level', 1)->get();

        $link_where=array();//分页条件数组
        $link_where['page_size']=$this->page_size;
        $str = '';
        if ($province != -1) {
            $arr=DB::table('cfg_locations')->where('id',$province)->first();
            $link_where['province']=$province;
            $str .= " and d.province='$arr->name' ";

        }
        if ($city != -1) {
            $arr=DB::table('cfg_locations')->where('id',$city)->first();
            $link_where['city']=$city;
            $str .= " and d.city='$arr->name' ";

        }
        if (!empty($str)) {
            $infos = DB::table(DB::raw("(select `a`.`id`, `a`.`name` as `county`, `b`.`name` as `city`, `c`.`name` as `province` from `cfg_locations` as `a` left join `cfg_locations` as `b` on `a`.`parent_id` = `b`.`id` left join `cfg_locations` as `c` on `b`.`parent_id` = `c`.`id` where `a`.`level` = '3') AS d where 1=1 $str "))->paginate($this->page_size);

        } else {
            $infos = DB::table('cfg_locations AS a')->select('a.id', 'a.name AS county', 'b.name AS city', 'c.name AS province')->leftJoin('cfg_locations AS b', 'a.parent_id', '=', 'b.id')
                ->leftJoin('cfg_locations AS c', 'b.parent_id', '=', 'c.id')->where('a.level', '3')->paginate($this->page_size);

        }
        return view('admin.ks.location.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str, 'provinces' => $provinces, 'province' => $province,'city' => $city,'link_where'=>$link_where]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $provinces = DB::table('cfg_locations')->where('level', 1)->get();
        return view('admin.ks.location.create',['provinces' => $provinces]);
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
        $province=$request->province;
        $city=$request->city;
        $county=$request->county;
        if($province==-1){
            return redirect()->back()->withInput()->with('success', '请选择省');
        }
        if($city==-1){
            return redirect()->back()->withInput()->with('success', '请选择市');
        }
        $count=DB::table('cfg_locations')->where('parent_id',$city)->where('name',$county)->count();
        if (!empty($count)){
            return redirect()->back()->withInput()->with('success', '同一个省市下不允许同名区县');
        }

        DB::table('cfg_locations')->insert([
            'name'=>$county,
            'parent_id'=>$city,
            'level'=>3,
        ]);

        return redirect()->back()->with('success', '添加成功');




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
        $info=DB::select("SELECT a.id,a.`name` AS county,a.parent_id AS city,(SELECT parent_id FROM cfg_locations WHERE a.parent_id=id) AS province FROM `cfg_locations` AS a WHERE a.id=$id")[0];
        $provinces = DB::table('cfg_locations')->where('level', 1)->get();
        return view('admin.ks.location.create',['provinces' => $provinces,'info'=>$info]);
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
        $province=$request->province;
        $city=$request->city;
        $county=$request->county;
        if($province==-1){
            return redirect()->back()->withInput()->with('success', '请选择省');
        }
        if($city==-1){
            return redirect()->back()->withInput()->with('success', '请选择市');
        }

        $count=DB::table('cfg_locations')->where('parent_id',$city)->where('name',$county)->where('id','!=',$id)->count();
        if (!empty($count)){
            return redirect()->back()->withInput()->with('success', '同一个省市下不允许同名区县');
        }

        DB::table('cfg_locations')->where('id',$id)->update([
            'name'=>$county,
            'parent_id'=>$city,
            'level'=>3,
        ]);
        return redirect()->back()->with('success', '更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('cfg_locations')->where('id',$id)->delete();
        return response()->json([
            'msg' => 1
        ]);
    }

    //获取省市区数据
    public function getData(Request $request)
    {
        $id = $request->id;
        $data = DB::table('cfg_locations')->where('parent_id', $id)->get();
        return response()->json(['data' => $data]);
    }
}
