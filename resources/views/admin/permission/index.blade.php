@extends('admin.layouts.default')
@section('t1','权限')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            @if(Auth::user()->can('admin.permission.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.permission.create')}}" class="btn btn-primary">新增</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <form id="user_ids">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>图标</th>
                                    <th>菜单名称</th>
                                    <th>权限标识</th>
                                    <th>url</th>
                                    <th>是否显示</th>
                                    <th>描述</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td><i class="{{$permission['icon']}}" aria-hidden="true"></i></td>
                                        <td>{{$permission['delimiter'].$permission['display_name']}}</td>
                                        <td>{{$permission['name']}}</td>
                                        <td>{{$permission['url']}}</td>
                                        <td>@if($permission['ishow']==1) 是 @else 否 @endif</td>
                                        <td>{{$permission['description']}}</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.permission.show',$permission['id'])}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-eye " aria-hidden="true">查看</i></a>

                                            <a class=" op_edit"  href="{{route('admin.permission.edit',$permission['id'])}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>

                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.permission.destroy',$permission['id'])}}')">
                                                <i class="fa  fa-trash-o " aria-hidden="true">删除</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
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
    <script>

        //有查看权限，显示查看
        @if(Auth::user()->can('admin.permission.show'))
             $(".op_show").show();
        @endif

        //有修改权限，显示修改
        @if(Auth::user()->can('admin.permission.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.permission.destroy'))
            $(".op_destroy").show();
        @endif

    </script>

@endsection