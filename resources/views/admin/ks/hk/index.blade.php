@extends('admin.layouts.default')
@section('t1','热搜关键字')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form id="search" class="form-inline" action="{{route('admin.ks.hk.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-9 col-xs-10">
                                    状态
                                    <select name="is_recommend" class="form-control">
                                        <option  value="-1">全部</option>
                                        <option @if($is_recommend==1) selected @endif value="1">推荐</option>
                                        <option @if($is_recommend==0) selected @endif value="0">未推荐</option>
                                    </select>
                                    <input type="hidden" name="search_count" >
                                    <div class="input-group">
                                        <input value="{{$where_str}}" name="where_str" type="text" class="form-control"
                                               placeholder="热搜关键字">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>

                                </div>
                            </form>
                            @if(Auth::user()->can('admin.ks.hk.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.ks.hk.create')}}" class="btn btn-primary">新增</a>
                                </div>
                            @endif

                        </div>
                    </div>
                    <!--box-header-->
                    <!--box-body-->
                    <form id="ids">
                        <div class="box-body table-responsive no-padding">
                            @if(count($infos) > 0)
                                <table class="table table-hover">
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th width="30%">热搜关键字</th>
                                    <th>状态</th>
                                    <th onclick="search_count_order('{{ $search_order_by or 'asc' }}');">搜索次数</th>
                                    <th width="20%">操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        <th><input class="minimal" name="ids[]" type="checkbox"
                                                   value="{{$info->id}}"></th>
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>

                                        <td>{{$info->searchname}}</td>
                                        <td>@if($info->is_recommend==1) 推荐 @else 不推荐 @endif</td>
                                        <td>{{$info->search_count}}</td>
                                        <td>
                                            <a class=" op_show" href="javascript:updateStatus('{{route('admin.ks.hk.updateStatus',$info->id)}}')"
                                               style="margin-right: 10px;display: none">
                                               @if($info->is_recommend==1) 取消 @else 推荐 @endif</a>
                                            <a class=" op_edit"  href="{{route('admin.ks.hk.edit',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.hk.destroy',$info->id)}}')">
                                                <i class="fa  fa-trash-o " aria-hidden="true">删除</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            @else
                                <div class="col-xs-12 text-center">
                                    <h3>暂无查询记录</h3>
                                </div>
                            @endif
                        </div>
                    </form>
                    <!--box-body-->
                    <!--box-footer-->
                    <div class="box-footer ">
                        @if(Auth::user()->can('admin.ks.hk.batch_destroy'))
                            <div class="btn-group">
                                <button onclick="selectAll()" type="button" class="btn btn-default">全选</button>
                                <button onclick="reverse()" type="button" class="btn btn-default">反选</button>
                                <a href="javascript:batch_destroy()" class="btn btn-danger">批量删除</a>
                            </div>
                        @endif
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
        //屏蔽和显示
        @if(Auth::user()->can('admin.ks.hk.updateStatus'))
             $(".op_show").show();
        @endif
        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.hk.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.hk.destroy'))
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

        function search_count_order(orderBy) {
            $("input[name='search_count']").val(orderBy);
            $("#search").submit();
        }
        //批量删除
        function batch_destroy() {
            $cbs = $('table input[type="checkbox"]:checked');
            if ($cbs.length > 0) {
                layer.confirm('确认删除？', {
                    btn: ['确认', '取消']
                },function () {
                    $.ajax({
                        url: '{{route("admin.ks.hk.batch_destroy")}}',
                        type: 'post',
                        data: $("#ids").serialize(),
                        success: function (data) {
                            if (data.msg == 1) {
                                layer.alert('删除成功');
                                location.reload();
                            } else {
                                layer.alert('删除失败');
                            }
                        }
                    });
                });

            } else {layer.alert('请选中要删除的列');}}
        //全选
        function selectAll() {
            $('input[type="checkbox"].minimal').iCheck('check')
        }
        //反选
        function reverse() {
            $('input[type="checkbox"].minimal').each(function () {
                if ($(this).is(":checked")) {
                    $(this).iCheck('uncheck');
                } else {
                    $(this).iCheck('check');
                }});}


    </script>
    @include('admin.common.layer_del')
@endsection