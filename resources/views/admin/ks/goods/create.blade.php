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
                            <td width="80%">
                                @foreach($banner as $item)
                                    <a href="{{$item->attr_value}}" title="点我查看大图"><img src="{{$item->attr_value}}" class="img-responsive img-rounded" style="width: 20%; display: inline-block;"></a>
                                @endforeach
                            </td>
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
                            <td>{{$sc_name}}</td>
                        </tr>
                        <tr>
                            <td>所属品类</td>
                            <td>{{$category}}</td>
                        </tr>
                        <tr>
                            <td>商品应用</td>
                            <td>
                                @foreach($apply as $item)
                                    <h5>{{$item->title}}</h5>
                                    <video src="{{$item->videourl}}" controls="controls">
                                    </video>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>商品详情</td>
                            <td>

                                <iframe src="{{$descrip_link}}" width="400" height="700" longdesc="w3school.txt">
                                    <p>您的浏览器不支持框架。</p>
                                </iframe>

                                {{--<a href="{{$descrip_link}}" target="_blank">商品详情链接</a>--}}

                                {{--<div style="width: 600px">--}}
                                {{--@foreach($descrip as $item)--}}
                                    {{--@if($item->attr_type == 1)--}}
                                        {{--<div>--}}
                                            {{--<textarea style="width: 100%" rows="10" >{{$item->attr_value}}</textarea>--}}
                                        {{--</div>--}}
                                    {{--@endif--}}
                                    {{--@if($item->attr_type == 2)--}}
                                        {{--<div style="width: 20%; display: inline-block;">--}}
                                            {{--<a href="{{$item->attr_value}}" title="点我查看大图"><img src="{{$item->attr_value}}" class="img-responsive img-rounded" ></a>--}}
                                        {{--</div>--}}
                                    {{--@endif--}}
                                    {{--@if($item->attr_type == 3)--}}
                                        {{--<div>--}}
                                            {{--<video width="100%" src="{{$item->attr_value}}" controls="controls"></video>--}}
                                        {{--</div>--}}
                                    {{--@endif--}}
                                    {{--@if($item->attr_type == 4)--}}
                                        {{--<hr />--}}
                                    {{--@endif--}}
                                {{--@endforeach--}}
                                {{--</div>--}}
                            </td>
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