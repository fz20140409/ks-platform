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
                            <form class="form-inline" action="{{route('admin.ks.goods.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-11 col-xs-10">
                                    所在区域
                                    <select name="area" class="form-control">
                                        <option value="-1">全部</option>
                                        @foreach($provices as $item)
                                            <option @if($area==$item) selected @endif value="{{$item}}">{{$item}}</option>
                                            @endforeach

                                    </select>
                                    所属品类
                                    <select name="cate_name" class="form-control" style="width: 10%">
                                        <option value="-1">全部</option>
                                        @foreach($cates as $cate)
                                            <option @if($cate['cat_name']==$cate_name) selected @endif  value="{{$cate['cat_name']}}">{{$cate['delimiter'].$cate['cat_name']}}</option>
                                        @endforeach
                                    </select>
                                    所属品牌
                                    <select name="brand" class="form-control" style="width: 10%">
                                        <option value="-1">全部</option>
                                        @foreach($brands as $item)
                                            <option  @if($brand==$item->bid) selected @endif value="{{$item->bid}}">{{$item->zybrand}}</option>
                                        @endforeach

                                    </select>
                                    商品标签
                                    <select name="label" class="form-control">
                                        <option value="-1">全部</option>
                                        <option  @if($label==1) selected @endif value="1">热门商品</option>
                                        <option @if($label==2) selected @endif value="2">新品推荐</option>
                                        <option @if($label==3) selected @endif value="3">促销商品</option>
                                    </select>
                                    <div class="input-group col-lg-4">
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
                            @if(count($infos) > 0)
                                <table class="table table-hover">
                                <tr>
                                    {{--<th></th>--}}
                                    <th>ID</th>
                                    <th>地区</th>
                                    <th>企业/商铺名称</th>
                                    <th>所属品牌</th>
                                    <th>商品名称</th>
                                    <th>商品标题</th>
                                    {{--<th>所属品类</th>--}}
                                    <th>价格1</th>
                                    <th>价格2</th>
                                    <th>商品标签</th>
                                    <th>销量</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        {{--<th><input class="minimal" name="user_ids[]" type="checkbox"--}}
                                                   {{--value="{{$info->goods_id}}"></th>--}}
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>
                                        <td>{{$info->provice}}</td>
                                        <td>{{$info->company}}</td>
                                        <td>{{$info->zybrand}}</td>
                                        <td>{{$info->goods_smallname}}</td>
                                        <td>{{$info->goods_name}}</td>
                                        {{--<td>{{$info->cat_name}}</td>--}}

                                        <td>{{$info->price}} {{isset($info->price) ? '/' : ''}} {{$info->spec_unic}}</td>
                                        <td>{{$info->changespec_price}} {{isset($info->changespec_price) ? '/' : ''}} {{$info->changespec_name}}</td>
                                        <td>@if($info->is_hot==1) <span style="color: #00a7d0">热门商品</span> @endif @if($info->is_new==1) <span style="margin-left: 2px;color: #00a7d0">新品推荐</span> @endif  @if($info->is_cuxiao==1) <span style="margin-left: 2px;color: #00a7d0">促销商品</span> @endif</td>
                                        <td>{{$info->sell_count }}</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.ks.goods.show',$info->goods_id)}}"
                                               style="margin-right: 10px;display: none">
                                                    <i class="fa fa-eye " aria-hidden="true">查看</i></a>
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
        @if(Auth::user()->can('admin.ks.goods.show'))
             $(".op_show").show();
        @endif

    </script>
    @include('admin.common.layer_del')
@endsection