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
                            <form class="form-inline" action="{{route('admin.ks.um.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-8 col-xs-10">
                                    使用状态
                                    <select name="enabled" class="form-control">
                                        <option value="-1">全部</option>
                                        <option @if($enabled==1) selected @endif value="1">正常</option>
                                        <option @if($enabled==0) selected @endif value="0">禁用</option>

                                    </select>
                                    类型
                                    <select name="type" class="form-control">
                                        <option value="-1">全部</option>
                                        @foreach($types as $item)
                                            <option @if($type==$item->id) selected @endif value="{{$item->id}}">{{$item->type_name}}</option>
                                        @endforeach
                                    </select>
                                    认证信息
                                    <select name="is_auth" class="form-control">
                                        <option value="-1">全部</option>
                                        <option  @if($is_auth==1) selected @endif value="1">已认证</option>
                                        <option @if($is_auth==0) selected @endif value="0">未认证</option>
                                    </select>
                                    <div class="input-group">
                                        <input value="{{$where_str}}" name="where_str" type="text" class="form-control"
                                               placeholder="手机号码/真实姓名/昵称">
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
                                    {{--<th></th>--}}
                                    <th>ID</th>
                                    <th>注册时间</th>
                                    <th>用户ID</th>
                                    <th>手机号码</th>
                                    <th>用户类型</th>
                                    <th>真实姓名</th>
                                    <th>昵称</th>
                                    <th>是否认证</th>
                                    <th>公司/店铺名称</th>
                                    <th>职位</th>
                                    <th>使用状态</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        {{--<th><input class="minimal" name="user_ids[]" type="checkbox"--}}
                                                   {{--value="{{$info->uid}}"></th>--}}
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>
                                        <td>{{$info->createtime}}</td>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->phone}}</td>
                                        <td>{{$type_arr[$info->utype]}}</td>
                                        <td>{{$info->IDname}}</td>
                                        <td>{{$info->username}}</td>
                                        <td>@if($info->iscertifi==1) 已认证 @else 未认证 @endif</td>
                                        <td>{{$info->company}}</td>
                                        <td>{{$info->post}}</td>
                                        <td>@if($info->enabled==1) 正常 @else 禁用 @endif</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.ks.um.show',$info->uid)}}"
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
        //有查看权限，显示查看
        @if(Auth::user()->can('admin.ks.um.show'))
             $(".op_show").show();
        @endif

    </script>
    @include('admin.common.layer_del')
@endsection