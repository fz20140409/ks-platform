@extends('admin.layouts.default')
@section('t1','商品')
@section('t2','详情')
    @section('css')
       <style>
           .box-body table tr td:first-child{
               color: #00a7d0;
           }
       </style>
        @endsection
@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <div class="box-header">

                </div>
                <div class=" box-body table-responsive no-padding">
                    <table class=" table table-hover table-bordered">
                        <tr>
                            <td width="20%">图片</td>
                            <td width="80%"><img src=""  class="img-rounded"></td>
                        </tr>
                        <tr>
                            <td>商品标题</td>
                            <td>{{$info->goods_name}}</td>
                        </tr>
                        <tr>
                            <td>商品简称</td>
                            <td>{{$info->goods_smallname}}</td>
                        </tr>
                        <tr>
                            <td>所属品牌</td>
                            <td>{{$info->zybrand}}</td>
                        </tr>
                        <tr>
                            <td>商品规格</td>
                            <td>
                                @foreach($spec as $item)
                                    <span style="margin-right: 5px">{{$item->spec_unic}}</span>
                                    @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>商品价格</td>
                            <td>
                                @foreach($spec as $item)
                                    <span style="margin-right: 5px">￥{{$item->price}}/{{$item->spec_unic}}</span>,
                                    @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>商品库存</td>
                            <td>
                                @foreach($spec as $item)
                                    <span style="margin-right: 5px">{{$item->kc}}{{$item->spec_unic}}</span>,
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>商品标签</td>
                            <td>@if($info->is_hot==1) <span style="color: #00a7d0">热门商品</span> @endif @if($info->is_new==1) <span style="margin-left: 2px;color: #00a7d0">新品推荐</span> @endif  @if($info->is_cuxiao==1) <span style="margin-left: 2px;color: #00a7d0">促销商品</span> @endif</td>
                        </tr>
                        <tr>
                            <td>店铺分类</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>所属品类</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>商品应用</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>商品详情</td>
                            <td>xxx</td>
                        </tr>
                    </table>
                </div>
                <div class="box-footer  ">
                    <a href="{{route('admin.ks.goods.index')}}" class="btn btn-default">返回</a>

                </div>


            </div>
        </div>
        </div>
    </section>
    @endsection