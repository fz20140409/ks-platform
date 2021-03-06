@extends('admin.layouts.default')
@if(isset($parent))
    @section('t1',$parent)
@else
    @section('t1','分类图标')
@endif

@section('t2','设置')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline"
                                  action="@if(isset($level)){{route('admin.ks.ci.showSub',$pid)}}@else {{route('admin.ks.ci.index')}}@endif">
                                @if(isset($level))
                                    <input type="hidden" name="level" value="{{$level}}">
                                @endif
                                <div class="col-lg-1 col-xs-4">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 col-xs-9">
                                    <div class="input-group">
                                        <input value="{{$where_str}}" name="where_str" type="text" class="form-control"
                                               placeholder="分类图标名称">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>

                                </div>
                                @if(Auth::user()->can('admin.ks.ci.create'))
                                    <div class="col-lg-2 col-xs-3 pull-right">
                                        @if(isset($pid))
                                            <a href="{{route('admin.ks.ci.create',['pid'=>$pid,'level'=>$level])}}"
                                               class="btn btn-primary">新增</a>
                                            @else
                                            <a href="{{route('admin.ks.ci.create')}}"
                                               class="btn btn-primary">新增</a>
                                            @endif
                                            @if(isset($level))

                                                    <a href="{{route('admin.ks.ci.index')}}"
                                                       class="btn btn-primary">返回</a>



                                            @endif

                                    </div>
                                @endif
                            </form>


                        </div>
                    </div>
                    <!--box-header-->
                    <!--box-body-->
                    <form id="ids">
                        <div class="box-body table-responsive no-padding">
                            @if(count($infos) > 0)
                            <table class="table table-hover">
                                <tr>
                                    {{--<th></th>--}}
                                    <th>序号</th>
                                    @if(!(isset($level)&&$level==3))
                                        <th>图片</th>
                                    @endif
                                    <th>
                                        @if(isset($level)&&$level==2)
                                            二级分类图标
                                        @elseif(isset($level)&&$level==3)
                                            三级分类图标
                                        @else
                                            一级分类图标
                                        @endif

                                    </th>
                                    <th>状态</th>

                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        {{--<th><input class="minimal" name="ids[]" type="checkbox"--}}
                                                   {{--value="{{$info->cid}}"></th>--}}
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>

                                            <td width="10%">@if(!empty($info->cicon)) <img class="img-responsive center-block" src="{{$info->cicon}}"> @endif</td>

                                        <td>{{$info->cname}}</td>
                                        <td>
                                            <a class="op_show" href="javascript:updateStatus('{{route('admin.ks.ci.updateStatus',$info->cid)}}')"
                                               style="margin-right: 10px;display: none;">
                                                <i class="fa fa-eye " aria-hidden="true">@if($info->is_show==1) 屏蔽 @else 显示 @endif</i></a>
                                        </td>
                                        <td>
                                            {{--{{route('admin.ks.ci.edit',$info->uid)}}--}}
                                            @if(isset($pid))
                                                <a class=" op_edit"
                                                   href="{{route('admin.ks.ci.edit',[$info->cid,'pid'=>$pid,'level'=>$level])}}"
                                                   style="margin-right: 10px;display: none">
                                                    <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            @else
                                                <a class=" op_edit"
                                                   href="{{route('admin.ks.ci.edit',$info->cid)}}"
                                                   style="margin-right: 10px;display: none">
                                                    <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            @endif

                                                <a class=" op_showSub"
                                                   href=" @if(isset($level)&&$level==2){{route('admin.ks.ci.showSub',[$info->cid,'level'=>3])}}@else{{route('admin.ks.ci.showSub',[$info->cid,'level'=>2])}}@endif"
                                                   style="margin-right: 10px;display: none">
                                                    <i class="fa fa-pencil-square-o " aria-hidden="true">编辑子分类</i></a>


                                            <a style="display: none" class=" op_destroy"
                                               href="javascript:del('@if(isset($level)&&$level==2){{route('admin.ks.ci.destroy',[$info->cid,'level'=>3])}}@else{{route('admin.ks.ci.destroy',[$info->cid,'level'=>2])}}@endif')">
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
                        {{--@if(Auth::user()->can('admin.ks.ci.batch_destroy'))
                            <div class="btn-group">
                                <button onclick="selectAll()" type="button" class="btn btn-default">全选</button>
                                <button onclick="reverse()" type="button" class="btn btn-default">反选</button>
                                <a href="javascript:batch_destroy()" class="btn btn-danger">批量删除</a>
                            </div>
                        @endif--}}
                        <div style="float: right">
                            @if(isset($level))
                                {{$infos->appends(['where_str' => $where_str,'page_size'=>$page_size,'level'=>$level])->links()}}
                            @else
                                {{$infos->appends(['where_str' => $where_str,'page_size'=>$page_size])->links()}}
                            @endif

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
    <link rel="stylesheet" href="/plugins/bootstrap-fileinput/css/fileinput.min.css">
    <link rel="stylesheet" href="/plugins/bootstrapvalidator/css/bootstrapValidator.min.css">
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
        @if(Auth::user()->can('admin.ks.ci.updateStatus'))
            $(".op_show").show();
        @endif
        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.ci.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.ci.destroy'))
            $(".op_destroy").show();
        @endif
        //子分类
        @if(Auth::user()->can('admin.ks.ci.showSub'))
            $(".op_showSub").show();
        @endif
       {{-- //批量删除
        function batch_destroy() {
            $cbs = $('table input[type="checkbox"]:checked');
            if ($cbs.length > 0) {
                layer.confirm('确认删除？', {
                    btn: ['确认', '取消']
                }, function () {
                    var url = '';
                    $.ajax({
                        url: url,
                        type: 'post',
                        data: $("#ids").serialize(),
                        success: function (data) {
                            if (data.msg == 1) {
                                layer.alert('删除成功');
                                location.reload();
                            } else {
                                layer.confirm(data.msg, {
                                    btn: ['确认', '取消']
                                }, function () {
                                    url = url + '?flag=true';
                                    $.ajax({
                                        url: url,
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
                                    })
                                });
                            }

                        }
                    });
                });

            } else {
                layer.alert('请选中要删除的列');
            }
        }
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
                }
            });
        }--}}

        //删除
        function del(url) {
            layer.confirm('确认删除？', {
                btn: ['确认', '取消']
            }, function () {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function (data) {
                        if (data.msg == -1) {
                            layer.alert(data.info);
                            return false;
                        }
                        if (data.msg == 1) {
                            layer.alert('删除成功');
                            location.reload();
                        } else {
                            layer.confirm(data.msg, {
                                btn: ['确认', '取消']
                            }, function () {
                                $.ajax({
                                    url: url,
                                    type: 'DELETE',
                                    data: {'flag': true},
                                    success: function (data) {
                                        if (data.msg == 1) {
                                            layer.alert('删除成功');
                                            location.reload();
                                        } else {
                                            layer.alert('删除失败');
                                        }
                                    }
                                })
                            });
                        }
                    }
                });
            });
        }

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

@endsection