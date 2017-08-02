@extends('admin.layouts.default')
@section('t1','合作机会')
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


                                <div class="col-lg-10 col-xs-10">
                                    用户类型
                                    <select name="" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    分类标签
                                    <select name="" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    状态
                                    <select name="" class="form-control">
                                        <option>xxx</option>
                                    </select>
                                    标题
                                    <input class="form-control">
                                    公司或店铺名称
                                    <input class="form-control">
                                    <button class="btn btn-default" type="submit">查询</button>

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
                                    <th>发布时间</th>
                                    <th>分类标签</th>
                                    <th>公司/店铺的名称</th>
                                    <th>标题</th>
                                    <th>浏览量</th>
                                    <th>回复数</th>
                                    <th>被优化次数</th>
                                    <th>用户类型</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $info)
                                    <tr>
                                        <th><input class="minimal" name="user_ids[]" type="checkbox"
                                                   value="{{$info->sr_id}}"></th>
                                        <td>{{$info->sr_id}}</td>
                                        <td>{{$info->phone}}</td>
                                        <td>{{$info->provice}}</td>
                                        <td>{{$info->type_name}}</td>
                                        <td>{{$info->company}}</td>
                                        <td>{{$info->iscertifi}}</td>
                                        <td>{{$info->honesty}}</td>
                                        <td>{{$info->favor}}</td>
                                        <td>{{$info->goods_num}}</td>
                                        <td>{{$info->is_yz}}</td>
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
        @if(Auth::user()->can('admin.ks.user_info.show'))
             $(".op_show").show();
        @endif

    </script>
    @include('admin.common.layer_del')
@endsection