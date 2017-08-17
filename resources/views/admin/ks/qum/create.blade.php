@extends('admin.layouts.default')
@section('t1','商家')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.ks.qum.create')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-8 col-xs-10">
                                    所在区域
                                    <select name="provice" class="form-control">
                                        <option value="-1">全部</option>
                                        @foreach($provices as $item)

                                            <option @if($item->provice==$provice) selected @endif value="{{$item->provice}}">{{$item->provice}}</option>
                                            @endforeach

                                    </select>

                                    <div class="input-group">
                                        <input value="{{$where_str}}" name="where_str" type="text" class="form-control"
                                               placeholder="企业/商铺名称">
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">查询</button>
                                    </span>
                                    </div>

                                </div>
                            </form>
                            <div class="col-lg-2 col-xs-2 pull-right">
                                <a href="{{route('admin.ks.qum.index')}}" class="btn btn-primary">返回</a>
                            </div>

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
                                    <th>所在区域</th>
                                    <th>企业/商铺名称</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        <th><input class="minimal" name="user_ids[]" type="checkbox"
                                                   value="{{$info->mid}}"></th>
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>
                                        <td>{{$info->provice}}</td>
                                        <td>{{$info->company}}</td>
                                        <td>
                                            <a   class=" op_add_qum"  href="javascript:add_qum('{{route('admin.ks.qum.add_qum',$info->mid)}}')">
                                                添加优质商家</a>
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
                            {{$infos->appends($link_where)->links()}}
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
        //添加优质商家
        @if(Auth::user()->can('admin.ks.qum.add_qum'))
             $(".op_add_qum").show();
        @endif
        
        function add_qum(url) {
            $.ajax({
                url: url,
                type: 'get',
                async: false,
                success: function (data) {
                    if (data.msg == 1) {
                        layer.alert('操作成功');
                        location.reload();
                    } else {
                        layer.alert('操作失败');
                    }
                }
            });
            
        }
    </script>
@endsection