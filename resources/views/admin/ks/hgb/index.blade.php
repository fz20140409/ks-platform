@extends('admin.layouts.default')
@section('t1','首页热门商品')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form id="ids">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>图片</th>
                                    <th>分类</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $info)
                                    <tr>
                                        <th><input class="minimal" name="ids[]" type="checkbox"
                                                   value="{{$info->id}}"></th>
                                        <td>{{$info->id}}</td>
                                        <td width="10%"><a href="{{$info->img}}"><img class="img-responsive center-block" src="{{$info->img}}"></a></td>
                                        <td>{{$info->cat_name}}</td>
                                        <td>

                                            <a class=" op_edit"  href="{{route('admin.ks.hgb.edit',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </form>
                    <!--box-body-->
                    <!--box-footer-->

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
        //修改
        @if(Auth::user()->can('admin.ks.hgb.edit'))
            $(".op_edit").show();
        @endif

    </script>

@endsection