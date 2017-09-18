<?php

namespace App\Http\Controllers\Admin\Ks;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools\Category;

/**
 * 商品管理
 * Class GoodsController
 * @package App\Http\Controllers\Admin\Ks
 */
class GoodsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $str_where='1=1 and a.state != 3 and a.enabled = 1';
        $where_link['page_size']=$this->page_size;
        //
        $where_str = $request->where_str;
        if (!empty($where_str)) {
            $str_where.=" and (c.company like '%$where_str%' or a.goods_smallname like '%$where_str%' or a.goods_name like '%$where_str%')";
            $where_link['where_str']=$where_str;
        }
        //品牌
        $brand= isset($request->brand)?$request->brand:-1;
        if($brand!=-1){
            $str_where.=" and a.bid=$brand";

            $where_link['brand']=$brand;

        }
        //品类
        $cates=DB::table('cfg_category')->select('cat_id as id','parent_id as pid','cat_name')->where('enabled',1)->get()->toArray();
        $cates = array_map('get_object_vars', $cates);
        $cates=Category::toLevel($cates,0,"&nbsp;&nbsp;");
        $cate_name= isset($request->cate_name)?$request->cate_name:-1;
        if($cate_name!=-1){
            $str_where.=" and f.cat_name='$cate_name'";
            $where_link['cat_name']=$cate_name;
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
        $brands=DB::select("SELECT bid,zybrand FROM cfg_brand");


        $sql = "(SELECT a.goods_id,c.provice,c.company,d.zybrand,a.goods_smallname,a.goods_name,f.cat_name,a.sell_count,a.is_hot,a.is_new,is_cuxiao, gs.price, gs.spec_unic, gs.changespec_price, gs.changespec_name FROM `goods` AS a
LEFT JOIN merchant AS b ON a.sr_id=b.sr_id
LEFT JOIN `user` AS c ON c.uid=b.uid
LEFT JOIN cfg_brand AS d ON a.bid=d.bid
LEFT JOIN goods_spec AS gs ON a.goods_id=gs.good_id
LEFT JOIN goods_category_rela AS e ON a.goods_id=e.good_id
LEFT JOIN cfg_category AS f ON e.cat_id=f.cat_id where $str_where group by goods_id order by a.createtime desc) as g";
        $infos = DB::table(DB::raw($sql))->paginate($this->page_size);

        return view('admin.ks.goods.index', ['infos' => $infos, 'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes, 'where_str' => $where_str,'where_link' => $where_link,'provices'=>$provices,'brands'=>$brands,'area'=>$area,'brand'=>$brand,'label'=>$label,'cates'=>$cates,'cate_name'=>$cate_name]);

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
        // 图片
        $banner = DB::table('goods_attr')->where('good_id', $id)->where('attr_name', 'banner')->where('attr_type', 2)->where('enabled', 1)->get();
//        // 商品详情
//        $descrip = DB::table('goods_attr')->where('good_id', $id)->where('attr_name', 'descrip')->where('enabled', 1)->get();
        // 商品详情链接
        $sr_id = DB::table('goods')->where('goods_id', $id)->value('sr_id');
        $uid = DB::table('merchant')->where('sr_id', $sr_id)->value('uid');
        $descrip_link = "http://ks.fjmaimaimai.com:8588/shareNew/#/goodsDetails?uid={$uid}&good_id={$id}";

        // 店铺分类
        $sc_id = DB::table('goods_shopclassify')->where('good_id', $id)->where('enabled', 1)->value('sc_id');
        $sc_name = DB::table('merchant_shopclassify')->where('cat_id', $sc_id)->where('enabled', 1)->value('sc_name');
        // 所属品类
        $category = DB::select("select cc.cat_name from `goods_category_rela` as gc left join `cfg_category` as cc on gc.cat_id = cc.cat_id where gc.good_id = '{$id}' and gc.enabled = 1 and cc.enabled = 1 order by cc.parent_id ASC");
        $category = array_map('get_object_vars', $category);
        $category = array_column($category, 'cat_name');
        $category = implode('->', $category);
        // 商品应用
        $apply = DB::table('goods_apply')->where('good_id', $id)->where('enabled', 1)->get();
        //商品信息
        $info=DB::select("SELECT a.goods_name,a.goods_smallname,b.zybrand,a.is_new,a.is_hot,a.is_cuxiao FROM `goods` AS a
LEFT JOIN cfg_brand AS b ON a.bid=b.bid WHERE a.goods_id=$id")[0];
        // 价格
        $spec=DB::select("select price,kc,spec_unic from goods_spec where good_id=$id");
        $data = compact('banner', 'descrip', 'sc_name', 'category', 'apply', 'info', 'spec', 'descrip_link');

        return view('admin.ks.goods.create', $data);
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
        //
    }
}
