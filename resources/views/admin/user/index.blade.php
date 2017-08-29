@extends('admin.layouts.default')
@section('t1','用户')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.user.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-xs-10">
                                    <div class="input-group">
                                        <input value="{{$where_str}}" name="where_str" type="text" class="form-control"
                                               placeholder="邮箱/昵称">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>
                                </div>
                            </form>
                            @if(Auth::user()->can('admin.user.create'))
                            <div class="col-lg-2 col-xs-2 pull-right">
                                <a href="{{route('admin.user.create')}}" class="btn btn-primary">新增</a>
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
                                    <th>邮箱</th>
                                    <th>昵称</th>
                                    <th>最后登录ip</th>
                                    <th>最后登录时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($users as $k=>$user)
                                    <tr>

                                        <td>
                                            @if($user->id!=1)
                                            <input class="minimal" name="user_ids[]" type="checkbox" value="{{$user->id}}">
                                            @endif
                                        </td>

                                        <td>{{$k+1+($users->currentPage() -1)*$users->perPage()}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{long2ip($user->ip)}}</td>
                                        <td>{{$user->last_time}}</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.user.show',$user->id)}}"
                                               style="margin-right: 10px;display: none">
                                                    <i class="fa fa-eye " aria-hidden="true">查看</i></a>

                                            <a class=" op_edit"  href="{{route('admin.user.edit',$user->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            @if($user->id!=1)
                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.user.destroy',$user->id)}}')">
                                                <i class="fa  fa-trash-o " aria-hidden="true">删除</i></a>
                                                @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </form>
                    <!--box-body-->
                    <!--box-footer-->
                    <div class="box-footer ">
                        @if(Auth::user()->can('admin.user.batch_destroy'))
                        <div class="btn-group">
                            <button onclick="selectAll()" type="button" class="btn btn-default">全选</button>
                            <button onclick="reverse()" type="button" class="btn btn-default">反选</button>
                            <a href="javascript:batch_destroy()" class="btn btn-danger">批量删除</a>
                        </div>
                        @endif
                        <div style="float: right">
                            {{$users->appends(['where_str' => $where_str,'page_size'=>$page_size])->links()}}
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
        @if(Auth::user()->can('admin.user.show'))
             $(".op_show").show();
        @endif

        //有修改权限，显示修改
        @if(Auth::user()->can('admin.user.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.user.destroy'))
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
                        url: '{{route("admin.user.batch_destroy")}}',
                        type: 'post',
                        data: $("#user_ids").serialize(),
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