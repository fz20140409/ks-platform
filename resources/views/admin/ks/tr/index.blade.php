@extends('admin.layouts.default')
@section('t1','有话说角色')

@section('t2','设置')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            @if(Auth::user()->can('admin.ks.tr.create'))
                                <div class="col-lg-2 col-xs-3 pull-right">
                                    <a href="{{route('admin.ks.tr.create')}}"
                                       class="btn btn-primary">新增</a>
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
                                    {{--<th></th>--}}
                                    <th>序号</th>

                                    <th>头像</th>
                                    <th>角色名称</th>

                                    <th>管理后台帐号和昵称</th>
                                    <th>网易云的account和token</th>

                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        {{--<th><input class="minimal" name="ids[]" type="checkbox"--}}
                                                   {{--value="{{$info->cid}}"></th>--}}
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>

                                            <td width="10%">@if(!empty($info->role_icon)) <img class="img-responsive center-block" src="{{$info->role_icon}}"> @endif</td>

                                        <td>{{$info->role_name}}</td>
                                        <td>{{$info->email}}——{{$info->nkname}}</td>
                                        <td>{{$info->peerid}}——{{$info->token}}</td>

                                        <td>
                                            {{--{{route('admin.ks.tr.edit',$info->uid)}}--}}
                                            <a class=" op_edit"
                                               href="{{route('admin.ks.tr.edit',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>


                                            <a style="display: none" class=" op_destroy"
                                               href="javascript:del('{{route('admin.ks.tr.destroy',[$info->id])}}')">
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

                        <div style="float: right">
                            {{$infos->links()}}

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
        @if(Auth::user()->can('admin.ks.tr.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.tr.destroy'))
            $(".op_destroy").show();
        @endif

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
                        }
                    }
                });
            });
        }



    </script>

@endsection