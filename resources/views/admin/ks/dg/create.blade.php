@extends('admin.layouts.default')
@section('t1',"($title->title)-商品")
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.ks.dg.create')}}">
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
                                    <div class="col-lg-1 col-xs-2 pull-right">
                                        <a href="{{route('admin.ks.dg.index',['hid'=>$hid])}}" class="btn btn-primary">返回</a>
                                    </div>
                                    <input type="hidden" name="hid" value="{{$hid}}">
                                </div>
                            </form>

                        </div>
                    </div>
                    <!--box-header-->
                    <!--box-body-->
                    <form id="ids">
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
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        <th><input class="minimal" name="ids[]" type="checkbox"
                                                   value="{{$info->goods_id}}"></th>
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>
                                        <td>{{$info->provice}}</td>
                                        <td>{{$info->company}}</td>
                                        <td>{{$info->zybrand}}</td>
                                        <td>{{$info->goods_smallname}}</td>
                                        <td>{{$info->goods_name}}</td>
                                        <td>{{$info->cat_name}}</td>

                                        <td>xx</td>
                                        <td>xx</td>
                                        <td>@if($info->is_hot==1) <span style="color: #00a7d0">热门商品</span> @endif @if($info->is_new==1) <span style="margin-left: 2px;color: #00a7d0">新品推荐</span> @endif  @if($info->is_cuxiao==1) <span style="margin-left: 2px;color: #00a7d0">促销商品</span> @endif</td>
                                        <td>{{$info->sell_count }}</td>

                                    </tr>
                                @endforeach
                            </table>
                            <input type="hidden" value="{{$hid}}" name="hid">
                        </div>
                    </form>
                    <!--box-body-->
                    <!--box-footer-->
                    <div class="box-footer ">
                        @if(Auth::user()->can('admin.ks.dg.batch_add'))
                            <div class="btn-group">
                                <button onclick="selectAll()" type="button" class="btn btn-default">全选</button>
                                <button onclick="reverse()" type="button" class="btn btn-default">反选</button>
                                <a href="javascript:batch_add()" class="btn btn-primary">批量添加</a>
                            </div>
                        @endif
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

        //批量添加
        function batch_add() {
            $cbs = $('table input[type="checkbox"]:checked');
            if ($cbs.length > 0) {
                layer.confirm('确认添加？', {
                    btn: ['确认', '取消']
                },function () {
                    $.ajax({
                        url: '{{route("admin.ks.dg.batch_add")}}',
                        type: 'post',
                        data: $("#ids").serialize(),
                        success: function (data) {
                            if (data.msg == 1) {
                                layer.alert('添加成功');
                                location.reload();
                            } else {
                                layer.alert('添加失败');
                            }
                        }
                    });
                });

            } else {layer.alert('请选中要添加的列');}}
        //全选
        function selectAll() {
            $('input[type="checkbox"].minimal').iCheck('check')
        }
        //反选
        function reverse() {
            $('input[type="checkbox"].minimal').each(function () {
                if ($(this).is(":checked")) {
                    $(this).iCheck('uncheck');
                } else {
                    $(this).iCheck('check');
                }});}


    </script>
    @include('admin.common.layer_del')
@endsection