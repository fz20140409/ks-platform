@extends('admin.layouts.default')
@if(isset($parent))
    @section('t1',$parent)
    @else
    @section('t1','品类')
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
                                  action="@if(isset($level)){{route('admin.ks.category.showSub',$pid)}}@else {{route('admin.ks.category.index')}}@endif">
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
                                               placeholder="品类名称">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>

                                </div>
                                @if(Auth::user()->can('admin.ks.category.create'))
                                    <div class="col-lg-2 col-xs-3 pull-right">
                                        @if(isset($pid))
                                            <a href="{{route('admin.ks.category.create',['pid'=>$pid,'level'=>$level])}}"
                                               class="btn btn-primary">新增</a>
                                            @else
                                            <a href="{{route('admin.ks.category.create')}}"
                                               class="btn btn-primary">新增</a>
                                            @endif
                                            @if(isset($level))

                                                    <a href="{{route('admin.ks.category.index')}}"
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
                            <table class="table table-hover">
                                <tr>
                                    <th></th>
                                    <th>序号</th>
                                    @if(!(isset($level)&&$level==3))
                                        <th>图片</th>
                                    @endif
                                    <th>
                                        @if(isset($level)&&$level==2)
                                            二级品类
                                        @elseif(isset($level)&&$level==3)
                                            三级品类
                                        @else
                                            一级品类
                                        @endif

                                    </th>

                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        <th><input class="minimal" name="ids[]" type="checkbox"
                                                   value="{{$info->cat_id}}"></th>
                                        <td>{{$k+1}}</td>
                                        @if(!(isset($level)&&$level==3))
                                            <td width="10%">@if(!empty($info->cat_icon)) <img class="img-responsive center-block" src="{{$info->cat_icon}}"> @endif</td>
                                        @endif
                                        <td>{{$info->cat_name}}</td>
                                        <td>
                                            {{--{{route('admin.ks.category.edit',$info->uid)}}--}}
                                            @if(isset($pid))

                                                <a class=" op_edit"
                                                   href="{{route('admin.ks.category.edit',[$info->cat_id,'pid'=>$pid,'level'=>$level])}}"
                                                   style="margin-right: 10px;display: none">
                                                    <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            @else
                                                <a class=" op_edit"
                                                   href="{{route('admin.ks.category.edit',$info->cat_id)}}"
                                                   style="margin-right: 10px;display: none">
                                                    <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            @endif

                                            @if(!(isset($level)&&$level==3))
                                                <a class=" op_showSub"
                                                   href=" @if(isset($level)&&$level==2){{route('admin.ks.category.showSub',[$info->cat_id,'level'=>3])}}@else{{route('admin.ks.category.showSub',[$info->cat_id,'level'=>2])}}@endif"
                                                   style="margin-right: 10px;display: none">
                                                    <i class="fa fa-pencil-square-o " aria-hidden="true">编辑子分类</i></a>
                                            @endif

                                            <a style="display: none" class=" op_destroy"
                                               href="javascript:del('{{route('admin.ks.category.destroy',$info->cat_id)}}')">
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
                        @if(Auth::user()->can('admin.ks.category.batch_destroy'))
                            <div class="btn-group">
                                <button onclick="selectAll()" type="button" class="btn btn-default">全选</button>
                                <button onclick="reverse()" type="button" class="btn btn-default">反选</button>
                                <a href="javascript:batch_destroy()" class="btn btn-danger">批量删除</a>
                            </div>
                        @endif
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
        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.category.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.category.destroy'))
            $(".op_destroy").show();
        @endif
        //子分类
        @if(Auth::user()->can('admin.ks.category.showSub'))
            $(".op_showSub").show();
        @endif
        //批量删除
        function batch_destroy() {
            $cbs = $('table input[type="checkbox"]:checked');
            if ($cbs.length > 0) {
                layer.confirm('确认删除？', {
                    btn: ['确认', '取消']
                }, function () {
                    var url = '{{route("admin.ks.category.batch_destroy")}}';
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
        }

        //删除
        function del(url) {
            layer.confirm('确认删除？', {
                btn: ['确认', '取消']
            }, function () {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function (data) {
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

    </script>

@endsection