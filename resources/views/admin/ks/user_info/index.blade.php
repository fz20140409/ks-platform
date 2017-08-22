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
                            <form class="form-inline" action="{{route('admin.ks.user_info.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-8 col-xs-10">
                                    所在区域
                                    <select name="area" class="form-control">
                                        <option value="-1">全部</option>
                                        @foreach($provices as $item)
                                            <option @if($area==$item->provice) selected @endif value="{{$item->provice}}">{{$item->provice}}</option>
                                            @endforeach
                                    </select>
                                    类型
                                    <select name="type" class="form-control">
                                        <option value="-1">全部</option>
                                        @foreach($types as $item)
                                            <option @if($type==$item->type_name) selected @endif value="{{$item->type_name}}">{{$item->type_name}}</option>
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
                                               placeholder="手机号码">
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
                                    <th>企业/商铺名称</th>
                                    <th>认证信息</th>
                                    <th>诚信值</th>
                                    <th>在乎数</th>
                                    <th>商品数</th>
                                    <th>优质厂家/商家</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        <th><input class="minimal" name="user_ids[]" type="checkbox"
                                                   value="{{$info->sr_id}}"></th>
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>
                                        <td>{{$info->phone}}</td>
                                        <td>{{$info->provice}}</td>
                                        <td>{{$info->type_name}}</td>
                                        <td>{{$info->company}}</td>
                                        <td>@if($info->iscertifi==1) 已认证 @else 未认证 @endif</td>
                                        <td>{{$info->honesty}}</td>
                                        <td>{{$info->favor}}</td>
                                        <td>{{$info->goods_num}}</td>
                                        <td>@if($info->is_yz==1) 是 @else 否 @endif</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.ks.user_info.show',$info->sr_id)}}"
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
        @if(Auth::user()->can('admin.ks.user_info.show'))
             $(".op_show").show();
        @endif

    </script>
    @include('admin.common.layer_del')
@endsection