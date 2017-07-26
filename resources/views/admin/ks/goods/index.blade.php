@extends('admin.layouts.default')
@section('t1','商品')
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


                                <div class="col-lg-9 col-xs-10">
                                    所在区域
                                    <select name="" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    所属品类
                                    <select name="" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    所属品牌
                                    <select name="" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    商品标签
                                    <select name="" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    <div class="input-group col-lg-5">
                                        <input value="{{$where_str}}" name="where_str" type="text" class="form-control"
                                               placeholder="企业/商铺名称/商品名称/商品标题">
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
                                    <th>地区</th>
                                    <th>企业/商铺名称</th>
                                    <th>所属品牌</th>
                                    <th>商品名称</th>
                                    <th>商品标题</th>
                                    <th>所属品类</th>
                                    <th>价格1</th>
                                    <th>价格2</th>
                                    <th>商品标签</th>
                                    <th>销量</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $info)
                                    <tr>
                                        <th><input class="minimal" name="user_ids[]" type="checkbox"
                                                   value="{{$info->uid}}"></th>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->phone}}</td>
                                        <td>{{$info->provice}}</td>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->uid}}</td>
                                        <td>{{$info->uid}}</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.ks.goods.show',$info->uid)}}"
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
        //有查看权限，显示查看
        @if(Auth::user()->can('admin.ks.goods.show'))
             $(".op_show").show();
        @endif

    </script>
    @include('admin.common.layer_del')
@endsection