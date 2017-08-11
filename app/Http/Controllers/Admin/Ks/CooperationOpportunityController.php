<?php

namespace App\Http\Controllers\Admin\Ks;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\DB;

/**
 * 合作机会
 * Class CooperationOpportunityController
 * @package App\Http\Controllers\Admin\Ks
 */
class CooperationOpportunityController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = array();
        $link_where=array();//分页条件数组
        $type=isset($request->type)?$request->type:-1;
        $catename=isset($request->catename)?$request->catename:-1;
        $state=isset($request->state)?$request->state:-1;

        $title=$request->title;
        $company=$request->company;
        if ($type!=-1){
            $link_where['type']=$type;
            $where[]=['type_name','=',$type];
        }
        if ($catename!=-1){
            $link_where['catename']=$catename;
            $where[]=['cat','=',$catename];
        }
        if ($state!=-1){
            $link_where['state']=$state;
            $where[]=['state','=',$state];
        }
        if (isset($title)){
            $link_where['title']=$title;
            $where[]=['title','like',"%$title%"];
        }
        if (isset($company)){
            $link_where['company']=$company;
            $where[]=['company','like',"%$company%"];
        }

        $types=DB::select('SELECT DISTINCT (SELECT type_name FROM user_type_info WHERE b.mtype=id) AS type_name FROM `cooperation_opportunity`  AS a LEFT JOIN merchant AS b ON a.sr_id=b.sr_id');
        $cat_names=DB::select('SELECT DISTINCT (SELECT catename FROM cfg_coop_cate WHERE id=b.cid) catename FROM `cooperation_opportunity` AS a LEFT JOIN cooperation_opportunity_cate AS b ON a.id=b.coop_id');
        //条件
        $infos=DB::table(DB::raw('(select `a`.`id`, `a`.`createtime`, `b`.`company`, `a`.`title`, `a`.`state`, `a`.`optimize_count`, `a`.`assess_count`, `a`.`view_count`, (SELECT type_name FROM user_type_info WHERE id=c.mtype) AS type_name, (SELECT f.catename FROM cooperation_opportunity_cate AS d LEFT JOIN cfg_coop_cate AS f ON d.cid=f.id  WHERE d.coop_id=a.id ) AS cat from `cooperation_opportunity` as `a` left join `user` as `b` on `a`.`uid` = `b`.`uid` left join `merchant` as `c` on `a`.`sr_id` = `c`.`sr_id` where a.enabled=1) as g'))->where($where)->paginate($this->page_size);
        return view('admin.ks.coop.index',['infos'=>$infos,'page_size' => $this->page_size, 'page_sizes' => $this->page_sizes,'types'=>$types,
            'cat_names'=>$cat_names,'type'=>$type,'catename'=>$catename,'state'=>$state,
            'title'=>$title,'company'=>$company,'link_where'=>$link_where]);

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
        $comments=DB::select("SELECT a.content,a.create_time,b.username,b.uicon FROM `coop_comment` AS a LEFT JOIN `user` AS b ON a.uid=b.uid WHERE a.coop_id=$id");
        $info=DB::select("SELECT a.createtime,b.company ,(SELECT catename FROM cfg_coop_cate WHERE id=c.cid) catename,a.title ,a.intro,a.icon FROM `cooperation_opportunity` AS a LEFT JOIN `user` AS b ON a.uid=b.uid LEFT JOIN cooperation_opportunity_cate AS c ON a.id=c.coop_id WHERE a.id=$id")[0];
        return view('admin.ks.coop.create',compact('info','comments'));

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
        DB::table('cooperation_opportunity')->where('id',$id)->update([
            'enabled'=>0
        ]);;
        return response()->json([
            'msg' => 1
        ]);
    }
    function updateStatus($id){
        $info=DB::table('cooperation_opportunity')->where('id',$id)->first();
        if($info->state==1){
            DB::table('cooperation_opportunity')->where('id',$id)->update([
                'state'=>0
            ]);
        }else{
            DB::table('cooperation_opportunity')->where('id',$id)->update([
                'state'=>1
            ]);
        }
        return response()->json([
            'msg' => 1
        ]);

    }
}
