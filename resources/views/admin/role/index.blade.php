@extends('admin.layouts.default')
@section('t1','角色')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <!--todo-->
                            <form class="form-inline" action="{{route('admin.role.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-xs-10">
                                    <div class="input-group">
                                        <!--todo-->
                                        <input value="{{$where_str}}" name="where_str" type="text" class="form-control"
                                               placeholder="标识/名称">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>
                                </div>
                            </form>
                            <!--todo-->
                            @if(Auth::user()->can('admin.role.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.role.create')}}" class="btn btn-primary">新增</a>
                                </div>
                            @endif

                        </div>
                    </div>
                    <!--box-header-->
                    <!--box-body-->
                    <form id="user_ids">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <!--todo-->
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>标识</th>
                                    <th>名称</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($roles as $key => $role)
                                    <!--todo-->
                                    @if ($user_role == 3 || $role->id != 3)
                                    <tr>
                                        <th><input class="minimal" name="user_ids[]" type="checkbox"
                                                   value="{{$role->id}}"></th>
                                        <td>{{$k+1+($users->currentPage() -1)*$users->perPage()}}</td>
                                        <td>{{$role->name}}</td>
                                        <td>{{$role->display_name}}</td>
                                        <td>
                                            <a class=" op_edit"  href="{{route('admin.role.permission',$role->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">权限</i></a>
                                            <a class=" op_show" href="{{route('admin.role.show',$role->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-eye " aria-hidden="true">查看</i></a>

                                            @if ($role->id != $user_role)
                                                <a class=" op_edit"  href="{{route('admin.role.edit',$role->id)}}"
                                                   style="margin-right: 10px;display: none">
                                                    <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>

                                                <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.role.destroy',$role->id)}}')">
                                                    <i class="fa  fa-trash-o " aria-hidden="true">删除</i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </form>
                    <!--box-body-->
                    <!--box-footer-->
                    <!--todo-->
                    <div class="box-footer ">
                        <div style="float: right">
                            <!--todo-->
                            {{$roles->appends(['where_str' => $where_str,'page_size'=>$page_size])->links()}}
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

        //todo
        //权限
        @if(Auth::user()->can('admin.role.permission'))
             $(".op_show").show();
        @endif
        //有查看权限，显示查看
        @if(Auth::user()->can('admin.role.show'))
             $(".op_show").show();
        @endif

        //有修改权限，显示修改
        @if(Auth::user()->can('admin.role.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.role.destroy'))
            $(".op_destroy").show();
        @endif

    </script>
    @include('admin.common.layer_del')
@endsection