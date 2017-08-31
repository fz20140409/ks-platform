@extends('admin.layouts.default')
@section('t1','背景及icon设置')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.ks.mb.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-9 col-xs-10">
                                    类型
                                    <select name="type" class="form-control">
                                        <option @if($type==0) selected @endif value="0">店铺背景</option>
                                        <option @if($type==1) selected @endif value="1">平台icon</option>
                                        <option @if($type==2) selected @endif value="2">名片背景</option>
                                    </select>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">查询</button>
                                        </span>
                                    </div>

                                </div>
                            </form>
                            @if(Auth::user()->can('admin.ks.mb.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.ks.mb.create')}}" class="btn btn-primary">新增</a>
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
                                    <th>图标</th>
                                    <th>类型</th>

                                    <th width="30%">操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        <th><input class="minimal" name="user_ids[]" type="checkbox"
                                                   value="{{$info->id}}"></th>
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>

                                        <td width="10%"><img class="img-responsive center-block" src="{{$info->bgurl}}"></td>
                                        <td>@if(isset($info->type) && $info->type==0)店铺背景 @elseif(isset($info->type) && $info->type==1) 平台icon @else 名片背景 @endif</td>

                                        <td>
                                            <a class=" op_edit"  href="{{route('admin.ks.mb.edit', ['id' => $info->id, 'type' => $type])}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            <a style="display: none"  class="op_destroy"  href="javascript:del('{{route('admin.ks.mb.destroy', ['id' => $info->id, 'type' => $type])}}')">
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
        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.mb.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.mb.destroy'))
            $(".op_destroy").show();
        @endif
    </script>
    @include('admin.common.layer_del')
@endsection