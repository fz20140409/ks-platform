@extends('admin.layouts.default')
@section('t1','轮播图')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.ks.banner.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-9 col-xs-10">
                                    <div class="input-group">
                                        <input value="{{$where_str}}" name="where_str" type="text" class="form-control"
                                               placeholder="标题">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>

                                </div>
                            </form>
                            @if(Auth::user()->can('admin.ks.banner.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.ks.banner.create')}}" class="btn btn-primary">新增</a>
                                </div>
                            @endif

                        </div>
                    </div>
                    <!--box-header-->
                    <!--box-body-->
                    <form id="user_ids">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>轮播图</th>
                                    <th>标题</th>
                                    <th>链接类型</th>
                                    <th>跳转页面</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        <th><input class="minimal" name="user_ids[]" type="checkbox"
                                                   value="{{$info->id}}"></th>
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>
                                        <td width="15%">@if(!empty($info->url))<img class="img-responsive center-block"  src="{{$info->url}}"> @endif</td>
                                        <td>{{$info->title}}</td>
                                        <td>@if($info->type==0) 外部链接 @else
                                               @if($info->type==2)
                                                   内部链接--厂商
                                                   @elseif($info->type==3)
                                                    内部链接--优惠头条
                                                   @elseif($info->type==4)
                                                    内部链接--合作机会
                                                   @else

                                                   @endif
                                            @endif</td>
                                        <td>{{$info->r_url}}</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.ks.banner.show',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                    <i class="fa fa-eye " aria-hidden="true">屏蔽</i></a>
                                            <a class=" op_edit"  href="{{route('admin.ks.banner.edit',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.banner.destroy',$info->id)}}')">
                                                <i class="fa  fa-trash-o " aria-hidden="true">删除</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </form>
                    <!--box-body-->
                    <!--box-footer-->
                    <div class="box-footer ">
                        <div style="float: right">
                            {{$infos->appends(['where_str' => $where_str,'page_size'=>$page_size])->links()}}
                        </div>
                    </div>
                    <!--box-footer-->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('css')
    <link rel="stylesheet" href="/adminlte/plugins/iCheck/all.css">
@endsection

@section('js')
    <script src="/plugins/layer/layer.js"></script>
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script>
        $('input[type="checkbox"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
    </script>
    <script>
        //有查看权限，显示查看
        @if(Auth::user()->can('admin.ks.banner.show'))
             $(".op_show").show();
        @endif
        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.banner.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.banner.destroy'))
            $(".op_destroy").show();
        @endif

    </script>
    @include('admin.common.layer_del')
@endsection