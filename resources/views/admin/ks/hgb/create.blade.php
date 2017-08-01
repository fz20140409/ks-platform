@extends('admin.layouts.default')
@section('t1','首页热门商品')
@section('t2','修改')



@section('css')
    <link rel="stylesheet" href="/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/plugins/bootstrap-fileinput/css/fileinput.min.css">
    @endsection
@section('js')
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/piexif.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/sortable.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/purify.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/locales/zh.js"></script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
    </script>
    <script>
        $("#icon").fileinput({
            initialPreviewAsData: true,
            language: 'zh',
            maxFileSize: 1500,
            showUpload: false,
            allowedFileExtensions: ["jpg", "png", "gif"],
            @if(isset($info)&&!empty($info->img))
            initialPreview: ["{{$info->img}}"],
            @endif

        });
    </script>
    @include('admin.common.layer_tip')
    @endsection
@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="{{route('admin.ks.hgb.update',$info->id)}}">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif

                    <div class="box-body">
                        <div class="form-group">
                            <label for="searchname" class="col-sm-2 control-label">选择分类</label>

                            <div class="col-sm-8">
                               <select name="cat_id" class="form-control">
                                   @foreach($cats as $cat)
                                       <option  value="{{$cat->cat_id}}">{{$cat->cat_name}}</option>
                                       @endforeach

                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="col-sm-2 control-label">图片</label>
                            <div  class="col-sm-8">
                                <input id="icon" name="icon" type="file"  >
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.hgb.index')}}" class="btn btn-default">返回</a>
                        <button  type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection