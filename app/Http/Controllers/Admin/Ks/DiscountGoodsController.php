<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 优惠商品管理
 * Class DiscountGoodsController
 * @package App\Http\Controllers\Admin\Ks
 */
class DiscountGoodsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //头条id
        $hid=$request->hid;
        $title=DB::table('headline_info')->select('title')->where('hid',$hid)->first();


        $where_str = $request->where_str;

        //
        $where_link['page_size']=$this->page_size;
        //
        $str_where='';
        if (!empty($hid)){
            $str_where.=" and a.hid =$hid";
            $where_link['hid']=$hid;
        }

        if (!empty($where_str)) {

            $str_where.=" and b.goods_name like '%$where_str%'";
            $where_link['where_str']=$where_str;
        }

        $infos=DB::table(DB::raw("(SELECT a.id,b.goods_name,b.sell_count,a.create_time,b.state FROM `headline_attr`AS a LEFT JOIN goods AS b ON a.attr_value=b.goods_id WHERE a.attr_type='good' $str_where) as g"))->paginate($this->page_size);

        return view('admin.ks.dg.index', ['where_link' => $where_link,'infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str,'hid'=>$hid,'title'=>$title]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $hid=$request->hid;
        $title=DB::table('headline_info')->select('title')->where('hid',$hid)->first();
        $str_where='';
        $where_link['page_size']=$this->page_size;
        $where_link['hid']=$hid;

        $goods_ids=DB::table('headline_attr')->where([
            ['hid','=',$hid],['attr_type','=','good']
        ])->pluck('attr_value')->toArray();
       if (!empty($goods_ids)){
           $temp=implode(',',$goods_ids);
           $str_where.=" and a.goods_id not in($temp)";
           $where_link['hid']=$hid;
       }




        //
        $where_str = $request->where_str;
        if (!empty($where_str)) {
            $str_where.=" and c.company like '%$where_str%' or a.goods_smallname like '%$where_str%' or a.goods_name like '%$where_str%'";
            $where_link['where_str']=$where_str;
        }
        //品牌
        $brand= isset($request->brand)?$request->brand:-1;
        if($brand!=-1){
            //其他
            if($brand==-2){
                $arr=DB::select("SELECT a.goods_id FROM `goods` AS a
LEFT JOIN (SELECT bid AS i FROM cfg_brand ) AS b
ON a.bid=b.i WHERE b.i IS NULL");
                $temp=array();
                foreach ($arr as $item){
                    $temp[]=$item->goods_id;
                }
                if (!empty($temp)){
                    $temp=implode(',',$temp);
                }
                $str_where.=" and a.goods_id in ($temp)";


            }else{
                $str_where.=" and a.bid=$brand";
            }

            $where_link['brand']=$brand;

        }
        //区域
        $area= isset($request->area)?$request->area:-1;
        if ($area!=-1){
            $str_where.=" and c.provice='$area'";
            $where_link['area']=$area;
        }
        //商品标签
        $label= isset($request->label)?$request->label:-1;
        if ($label!=-1){
            switch ($label) {
                case 1:
                    $str_where.=" and a.is_hot=1";
                    break;
                case 2:
                    $str_where.=" and a.is_new=1";
                    break;
                case 3:
                    $str_where.=" and a.is_cuxiao=1";
                    break;
            }
            $where_link['label']=$label;

        }


        //区域数组
        $provices_arr=DB::select("SELECT DISTINCT provice FROM `user` WHERE uid in (SELECT uid FROM merchant WHERE sr_id IN (SELECT DISTINCT sr_id FROM `goods` ))");
        $provices=array();
        foreach ($provices_arr as $provice){
            $provices[]=$provice->provice;
        }
        //品牌数组
        $brands=DB::select("SELECT bid,zybrand FROM cfg_brand WHERE bid in (SELECT DISTINCT bid FROM goods)");




        $sql = "(SELECT a.goods_id,c.provice,c.company,d.zybrand,a.goods_smallname,a.goods_name,f.cat_name,a.sell_count,a.is_hot,a.is_new,is_cuxiao FROM `goods` AS a
LEFT JOIN merchant AS b ON a.sr_id=b.sr_id
LEFT JOIN `user` AS c ON c.uid=b.uid
LEFT JOIN cfg_brand AS d ON a.bid=d.bid
LEFT JOIN goods_category_rela AS e ON a.goods_id=e.good_id
LEFT JOIN cfg_category AS f ON e.cat_id=f.cat_id where 1=1 $str_where) as g";
        $infos = DB::table(DB::raw($sql))->paginate($this->page_size);


        return view('admin.ks.dg.create', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str,'where_link' => $where_link,'provices'=>$provices,'brands'=>$brands,'area'=>$area,'brand'=>$brand,'label'=>$label,'hid'=>$hid,'title'=>$title]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $searchname = $request->searchname;
        $is_recommend = $request->is_recommend;
        DB::table('cfg_hot_search')->insert([
            'searchname' => $searchname,
            'is_recommend' => $is_recommend,
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
        //
        $info = DB::table('cfg_hot_search')->where('id', $id)->first();
        return view('admin.ks.dg.create', compact('info'));
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
        $searchname = $request->searchname;
        $is_recommend = $request->is_recommend;
        DB::table('cfg_hot_search')->where('id', $id)->update([
            'searchname' => $searchname,
            'is_recommend' => $is_recommend,
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
        //
        DB::table('headline_attr')->where('id', $id)->delete();
        return response()->json([
            'msg' => 1
        ]);

    }



    public function batch_destroy(Request $request)
    {
        $ids = $request->ids;
        DB::table('headline_attr')->whereIn('id', $ids)->delete();
        return response()->json([
            'msg' => 1
        ]);

    }
    //批量添加优惠商品
    public function batch_add(Request $request)
    {
        $hid=$request->hid;
        $ids = $request->ids;
        $insert=[
            'hid'=>$hid,
            'enabled'=>1,
            'attr_type'=>'good',
            'create_time'=>date('Y-m-d H:i:s',time()),
        ];
        foreach ($ids as $id){
            $insert['attr_value']=$id;
            DB::table('headline_attr')->insert($insert);
        }
        return response()->json([
            'msg' => 1
        ]);

    }
}
