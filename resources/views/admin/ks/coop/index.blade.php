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
                            <form class="form-inline" action="{{route('admin.ks.coop.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-11 col-xs-10">
                                    用户类型
                                    <select name="type" class="form-control">
                                        <option value="-1">全部</option>
                                        @foreach($types as $item)
                                        <option @if($type==$item->type_name) selected @endif value="{{$item->type_name}}">{{$item->type_name}}</option>
                                            @endforeach
                                    </select>
                                    分类标签
                                    <select name="catename" class="form-control">
                                        <option value="-1">全部</option>
                                        @foreach($cat_names as $item)
                                            <option @if($catename==$item->catename) selected @endif value="{{$item->catename}}">{{$item->catename}}</option>
                                        @endforeach
                                    </select>
                                    状态
                                    <select name="state" class="form-control">
                                        <option @if($state==-1) selected @endif value="-1">全部</option>
                                        <option @if($state==1) selected @endif value="1">推荐</option>
                                        <option @if($state==0) selected @endif value="0">不推荐</option>
                                    </select>
                                    标题
                                    <input value="{{$title}}" name="title" class="form-control">
                                    公司或店铺名称
                                    <input value="{{$company}}" name="company"  class="form-control">
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
                                                   value="{{$info->id}}"></th>
                                        <td>{{$info->id}}</td>
                                        <td>{{$info->createtime}}</td>
                                        <td>{{$info->cat}}</td>
                                        <td>{{$info->company}}</td>
                                        <td>{{$info->title}}</td>
                                        <td>@if(empty($info->view_count)) 0 @else {{$info->view_count}} @endif</td>
                                        <td>@if(empty($info->assess_count)) 0 @else {{$info->assess_count}} @endif</td>
                                        <td>@if(empty($info->optimize_count)) 0 @else {{$info->optimize_count}} @endif</td>
                                        <td>{{$info->type_name}}</td>
                                        <td>@if($info->state==1) 推荐 @else 未推荐 @endif</td>
                                        <td>

                                            <a class=" op_show" href="{{route('admin.ks.coop.show',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                    <i class="fa fa-eye " aria-hidden="true">查看</i></a>
                                            <a  class=" op_updateStatus" href="javascript:updateStatus('{{route('admin.ks.coop.updateStatus',$info->id)}}')"
                                               style="margin-right: 10px;display: none;">
                                                <i class="fa fa-eye " aria-hidden="true">@if($info->state==1) 取消 @else 推荐 @endif</i></a>
                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.coop.destroy',$info->id)}}')">
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
                            {{$infos->appends([$link_where,'page_size'=>$page_size])->links()}}
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
        @if(Auth::user()->can('admin.ks.coop.show'))
             $(".op_show").show();

        @endif
        //屏蔽和显示
        @if(Auth::user()->can('admin.ks.coop.updateStatus'))
             $(".op_updateStatus").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.coop.destroy'))
            $(".op_destroy").show();
        @endif
        function updateStatus(url) {
            $.ajax({
                url: url,
                type: 'get',
                success: function ($data) {
                    if ($data.msg == 1) {
                        layer.alert('操作成功');
                        location.reload();
                    } else {
                        layer.alert('操作失败');
                    }
                }
            });

        }

    </script>
    @include('admin.common.layer_del')
@endsection