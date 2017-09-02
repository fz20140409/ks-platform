@extends('admin.layouts.default')
@section('t1','App版本管理')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.ks.av.index')}}">
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
                                               placeholder="版本号">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>

                                </div>
                                @if(Auth::user()->can('admin.ks.av.create'))
                                    <div class="col-lg-2 col-xs-3 pull-right">
                                        <a href="javascript:ce('{{route('admin.ks.av.create')}}',1)" class="btn btn-primary">新增</a>
                                    </div>
                                @endif

                            </form>

                            <form id="layer_ce" style="display: none" class="box-header form-horizontal" method="post">
                                {{csrf_field()}}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="download_type" class="col-sm-3 control-label">下载类型</label>

                                        <div class="col-xs-8">
                                            <select name="download_type" id="download_type" class="form-control">
                                                @foreach($download_type as $k=> $v)
                                                    <option value="{{$k}}">{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="version" class="col-sm-3 control-label">版本号</label>

                                        <div class="col-sm-8">
                                            <input value="" name="version" type="text" class="form-control" id="version" placeholder="版本号" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="terminal_type" class="col-sm-3 control-label">终端类型</label>

                                        <div class="col-sm-8">
                                            <select name="terminal_type" id="terminal_type" class="form-control">
                                                @foreach($terminal_type as $k=> $v)
                                                    <option value="{{$k}}">{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="url" class="col-sm-3 control-label">版本下载</label>

                                        <div class="col-sm-8">
                                            <input value="" name="url" type='url' class="form-control" id="url" placeholder="版本下载" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="update_info" class="col-sm-3 control-label">更新信息</label>

                                        <div class="col-sm-8">
                                            <textarea name="update_info" cols="5" rows="5" class="form-control" id="update_info" placeholder="更新信息"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer  ">
                                    <a href="" class="btn btn-default">返回</a>
                                    <a href="javascript:layer_ce_ajax()" class="btn btn-primary pull-right">保存</a>
                                </div>
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
                                    <th></th>
                                    <th>序号</th>
                                    <th>下载类型</th>
                                    <th>版本号</th>
                                    <th>终端类型</th>
                                    <th>版本下载</th>
                                    <th>更新信息</th>
                                    <th>创建时间</th>
                                    <th>更新时间</th>
                                    <th>操作人员</th>

                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        <th><input class="minimal" name="ids[]" type="checkbox"
                                                   value="{{$info->id}}"></th>
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>
                                        <td>{{$info->download_type}}</td>
                                        <td>{{$info->version}}</td>
                                        <td>{{$info->terminal_type}}</td>
                                        <td>{{$info->url}}</td>
                                        <td>{{$info->update_info}}</td>
                                        <td>{{$info->create_time}}</td>
                                        <td>{{$info->update_time}}</td>
                                        <td>{{$info->operator_name}}</td>
                                        <td>
                                            {{--{{route('admin.ks.av.edit',$info->uid)}}--}}
                                            <a class=" op_edit"  href="javascript:ce('{{route('admin.ks.av.edit',$info->id)}}',2)"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>

                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.av.destroy',$info->id)}}')">
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
                        @if(Auth::user()->can('admin.ks.av.batch_destroy'))
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
        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.av.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.av.destroy'))
            $(".op_destroy").show();
        @endif
        //批量删除
        function batch_destroy() {
            $cbs = $('table input[type="checkbox"]:checked');
            if ($cbs.length > 0) {
                layer.confirm('确认删除？', {
                    btn: ['确认', '取消']
                },function () {
                    var url='{{route("admin.ks.av.batch_destroy")}}';
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
    <script>
        function ce(url,flag) {
            if(flag==1){
                $('#layer_ce').attr('url','{{route('admin.ks.av.store')}}');
                layer.open({
                    title:'新增版本信息',
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['600px',''], //宽高
                    content:$('#layer_ce')
                });
            }
            if(flag==2){
                $.ajax({
                    type:'GET',
                    url:url,
                    success:function (data) {
                        $('#layer_ce').attr('url',data.link);
                        $("#layer_ce select[name='download_type']").val(data.download_type);
                        $("#layer_ce input[name='version']").val(data.version);
                        $("#layer_ce select[name='terminal_type']").val(data.terminal_type);
                        $("#layer_ce input[name='url']").val(data.url);
                        $("#layer_ce textarea[name='update_info']").text(data.update_info);

                        layer.open({
                            title:'修改版本信息',
                            type: 1,
                            skin: 'layui-layer-rim', //加上边框
                            area: ['600px',''], //宽高
                            content:$('#layer_ce')
                        });
                    }
                })
            }
        }
        //新增
        function layer_ce_ajax() {
            var url=$('#layer_ce').attr('url');
            $.ajax({
                type:'post',
                url:url,
                data:$('#layer_ce').serialize(),
                success:function (result) {
                    layer.closeAll();
                    if(result.msg==1){
                        layer.alert('操作成功');
                        location.reload();
                    }else{
                        layer.alert(result.msg);
                    }
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
                            layer.alert('删除失败');
                        }
                    }
                });
            });
        }

    </script>

@endsection