@extends('admin.layouts.default')
@section('t1',"($title->title)-优惠商品")
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.ks.dg.index')}}">
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
                                               placeholder="商品名称">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>

                                </div>
                                <input type="hidden" name="hid" value="{{$hid}}">
                            </form>
                            @if(Auth::user()->can('admin.ks.dg.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.ks.dg.create',['hid'=>$hid])}}" class="btn btn-primary">添加优惠商品</a> <a href="{{route('admin.ks.dh.index')}}" class="btn btn-primary">返回</a>
                                </div>
                            @endif

                        </div>
                    </div>
                    <!--box-header-->
                    <!--box-body-->
                    <form id="ids">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th width="30%">商品名称</th>
                                    <th>成交量</th>
                                    <th>订单数</th>
                                    <th>添加时间</th>

                                    <th width="20%">操作</th>
                                </tr>
                                @foreach($infos as $info)
                                    <tr>
                                        <th><input class="minimal" name="ids[]" type="checkbox"
                                                   value="{{$info->id}}"></th>
                                        <td>{{$info->id}}</td>

                                        <td>{{$info->goods_name}}</td>
                                        <td>xx</td>
                                        <td>{{$info->sell_count}}</td>
                                        <td>{{$info->create_time}}</td>
                                        <td>


                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.dg.destroy',$info->id)}}')">
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
                        @if(Auth::user()->can('admin.ks.dg.batch_destroy'))
                            <div class="btn-group">
                                <button onclick="selectAll()" type="button" class="btn btn-default">全选</button>
                                <button onclick="reverse()" type="button" class="btn btn-default">反选</button>
                                <a href="javascript:batch_destroy()" class="btn btn-danger">批量删除</a>
                            </div>
                        @endif
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


        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.dg.destroy'))
            $(".op_destroy").show();
        @endif
        //批量删除
        function batch_destroy() {
            $cbs = $('table input[type="checkbox"]:checked');
            if ($cbs.length > 0) {
                layer.confirm('确认删除？', {
                    btn: ['确认', '取消']
                },function () {
                    $.ajax({
                        url: '{{route("admin.ks.dg.batch_destroy")}}',
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