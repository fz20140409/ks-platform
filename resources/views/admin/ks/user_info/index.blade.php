@extends('admin.layouts.default')
@section('t1','网店')
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


                                <div class="col-lg-8 col-xs-10">
                                    所在区域
                                    <select name="page_size" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    类型
                                    <select name="page_size" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    认证信息
                                    <select name="page_size" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    <div class="input-group">
                                        <input value="" name="where_str" type="text" class="form-control"
                                               placeholder="手机号码/厂商姓名">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>

                                </div>
                            </form>

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
                                    <th>手机号</th>
                                    <th>所在区域</th>
                                    <th>类型</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $info)
                                    <tr>
                                        <th><input class="minimal" name="user_ids[]" type="checkbox"
                                                   value="{{$info->uid}}"></th>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->phone}}</td>
                                        <td>{{$info->provice}}</td>
                                        <td>{{$info->type_name}}</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.ks.user_info.show',$info->uid)}}"
                                               style="margin-right: 10px;display: none">
                                                    <i class="fa fa-eye " aria-hidden="true">查看</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </form>
                    <!--box-body-->
                    <!--box-footer-->
                    {{--<div class="box-footer ">
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
                    </div>--}}
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
        @if(Auth::user()->can('admin.ks.user_info.show'))
             $(".op_show").show();
        @endif

    </script>
    @include('admin.common.layer_del')
@endsection