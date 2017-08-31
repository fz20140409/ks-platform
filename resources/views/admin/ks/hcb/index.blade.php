@extends('admin.layouts.default')
@section('t1','品类热销榜banner设置')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            @if(Auth::user()->can('admin.ks.hcb.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.ks.hcb.create')}}" class="btn btn-primary">新增</a>
                                </div>
                            @endif

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
                                    <th>分类名称</th>
                                    <th>图片</th>


                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        {{--<th><input class="minimal" name="user_ids[]" type="checkbox"--}}
                                                   {{--value="{{$info->id}}"></th>--}}
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>
                                        <td>{{$info->cat_name}}</td>
                                        <td width="10%"><a href="{{$info->img}}"><img class="img-responsive center-block" src="{{$info->img}}"></a></td>
                                        <td >
                                            <a class=" op_edit"  href="{{route('admin.ks.hcb.edit',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.hcb.destroy',$info->id)}}')">
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
                            {{$infos->appends(['page_size'=>$page_size])->links()}}
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

        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.hcb.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.hcb.destroy'))
            $(".op_destroy").show();
        @endif
        


    </script>
    @include('admin.common.layer_del')
    @include('admin.common.layer_tip')
@endsection