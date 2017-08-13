@extends('admin.layouts.default')
@section('t1','优惠头条')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.ks.dh.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-9 col-xs-10">
                                    分类
                                    <select name="cate" class="form-control">
                                        <option value="-1">全部</option>
                                        @foreach($cates as $item)
                                            <option  @if($cate==$item->id) selected @endif value="{{$item->id}}">  {{$item->catename}}</option>
                                            @endforeach

                                    </select>
                                    状态
                                    <select name="status" class="form-control">
                                        <option value="-1">全部</option>
                                        <option @if($status==1) selected @endif value="1">正常</option>
                                        <option  @if($status==0) selected @endif value="0">屏蔽</option>
                                    </select>
                                    标题<input value="{{$title}}" name="title" type="text" class="form-control">
                                    <button class="btn btn-default" type="submit">查询</button>

                                </div>
                            </form>
                            @if(Auth::user()->can('admin.ks.dh.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.ks.dh.create')}}" class="btn btn-primary">新增</a>
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
                                    <th>发布时间</th>
                                    <th>标题</th>
                                    <th>分类</th>
                                    <th>浏览量</th>
                                    <th>被优化次数</th>
                                    <th>商品数量</th>
                                    <th>状态</th>
                                    <th>置顶</th>

                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $info)
                                    <tr>
                                        <th><input class="minimal" name="user_ids[]" type="checkbox"
                                                   value="{{$info->hid}}"></th>
                                        <td>{{$info->hid}}</td>
                                        <td>{{$info->createtime}}</td>
                                        <td>{{$info->title}}</td>
                                        <td>{{$info->catename}}</td>
                                        <td>{{$info->view_count}}</td>
                                        <td>{{$info->optimize_count}}</td>
                                        <td>{{$info->num}}</td>
                                        <td>@if($info->enabled==1) 正常 @else 屏蔽 @endif</td>
                                        <td>@if($info->is_top==1) 是 @else 否 @endif</td>
                                        <td>

                                            <a class=" op_edit"  href="{{route('admin.ks.dh.edit',$info->hid)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            <a  class=" op_updateStatus" href="javascript:updateStatus('{{route('admin.ks.dh.updateStatus',$info->hid)}}')"
                                                style="margin-right: 10px;display: none;">
                                                <i class="fa fa-eye " aria-hidden="true">@if($info->enabled==1) 屏蔽  @else 显示 @endif</i></a>
                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.dh.destroy',$info->hid)}}')">
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
                            {{$infos->appends($where_link)->links()}}
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
        //屏蔽和显示
        @if(Auth::user()->can('admin.ks.dh.updateStatus'))
             $(".op_updateStatus").show();
        @endif
        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.dh.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.dh.destroy'))
            $(".op_destroy").show();
        @endif

        function updateStatus(url) {
            $.ajax({
                url: url,
                type: 'get',
                success: function ($data) {
                    if ($data.msg == 1) {
                        layer.alert('操作成功');
                        location.reload();
                    } else {
                        layer.alert('操作失败');
                    }
                }
            });

        }


    </script>
    @include('admin.common.layer_del')
@endsection