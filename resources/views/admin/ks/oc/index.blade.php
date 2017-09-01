@extends('admin.layouts.default')
@section('t1','合作机会分类')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            @if(Auth::user()->can('admin.ks.oc.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.ks.oc.create')}}" class="btn btn-primary">新增</a>
                                </div>
                            @endif

                        </div>
                    </div>
                    <!--box-header-->
                    <!--box-body-->
                    <form id="ids">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover table-striped">
                                <tr >
                                    {{--<th></th>--}}
                                    <th>ID</th>
                                    <th>分类名称</th>
                                    <th>操作</th>
                                </tr>

                                @if(!empty($infos->total()))
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        {{--<th><input class="minimal" name="ids[]" type="checkbox"--}}
                                                   {{--value="{{$info->id}}"></th>--}}
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>
                                        <td>{{$info->catename}}</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.ks.oc.show',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                    <i class="fa fa-eye " aria-hidden="true">查看</i></a>
                                            <a class=" op_edit"  href="{{route('admin.ks.oc.edit',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>

                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.oc.destroy',$info->id)}}')">
                                                <i class="fa  fa-trash-o " aria-hidden="true">删除</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                    @else
                                    <tr>
                                        <td style="text-align: center" colspan="4">暂无记录</td>
                                    </tr>

                                    @endif
                            </table>
                        </div>
                    </form>
                    <!--box-body-->
                    <!--box-footer-->
                    @if(!empty($infos->total()))
                    <div class="box-footer ">
                       {{-- @if(Auth::user()->can('admin.ks.oc.batch_destroy'))
                            <div class="btn-group">
                                <button onclick="selectAll()" type="button" class="btn btn-default">全选</button>
                                <button onclick="reverse()" type="button" class="btn btn-default">反选</button>
                                <a href="javascript:batch_destroy()" class="btn btn-danger">批量删除</a>
                            </div>
                        @endif--}}
                        <div style="float: right">
                            {{$infos->appends(['page_size'=>$page_size])->links()}}
                        </div>
                    </div>
                    @endif
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
        @if(Auth::user()->can('admin.ks.oc.show'))
             $(".op_show").show();
        @endif

        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.oc.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.oc.destroy'))
            $(".op_destroy").show();
        @endif

        /*//批量删除
        function batch_destroy() {
            $cbs = $('table input[type="checkbox"]:checked');
            if ($cbs.length > 0) {
                layer.confirm('确认删除？', {
                    btn: ['确认', '取消']
                },function () {
                    $.ajax({
                        url: '',
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
                }});}*/
    </script>
    @include('admin.common.layer_del')
@endsection