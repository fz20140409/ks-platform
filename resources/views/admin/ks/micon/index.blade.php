@extends('admin.layouts.default')
@section('t1','与我有关-更多图标设置')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.ks.micon.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-9 col-xs-10">
                                    类型
                                    <select name="type" class="form-control">
                                        <option  value="-1">全部</option>
                                        <option @if($type==1) selected @endif value="1">常用功能</option>
                                        <option @if($type==2) selected @endif  value="2">网店功能</option>
                                        <option @if($type==3) selected @endif  value="3">生产贸易商功能</option>
                                    </select>
                                    <div class="input-group">
                                        <input value="{{$where_str}}" name="where_str" type="text" class="form-control"
                                               placeholder="名称">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>

                                </div>
                            </form>
                            @if(Auth::user()->can('admin.ks.micon.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.ks.micon.create')}}" class="btn btn-primary">新增</a>
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
                                    {{--<th></th>--}}
                                    <th>ID</th>
                                    <th>图标</th>
                                    <th >名称</th>
                                    <th>类型</th>

                                    <th>状态</th>

                                    <th width="30%">操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        {{--<th><input class="minimal" name="user_ids[]" type="checkbox"--}}
                                                   {{--value="{{$info->id}}"></th>--}}
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>

                                        <td width="10%"><a href="{{$info->icon}}"><img class="img-responsive center-block" src="{{$info->icon}}"></a></td>
                                        <td>{{$info->name}}</td>
                                        <td>@if($info->type==1)常用功能 @elseif($info->type==2) 网店功能 @elseif($info->type==3) 生产贸易商功能 @else @endif</td>
                                        <td>@if($info->status==1) 正常 @else 屏蔽 @endif</td>
                                        <td>

                                            <a class=" op_show" href="javascript:updateStatus('{{route('admin.ks.micon.updateStatus',$info->id)}}')"
                                               style="margin-right: 10px;display: none">
                                                    <i class="fa fa-eye " aria-hidden="true">@if($info->status==1) 屏蔽 @else 显示 @endif</i></a>
                                            <a class=" op_edit"  href="{{route('admin.ks.micon.edit',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.micon.destroy',$info->id)}}')">
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
        @if(Auth::user()->can('admin.ks.micon.updateStatus'))
             $(".op_show").show();
        @endif
        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.micon.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.micon.destroy'))
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