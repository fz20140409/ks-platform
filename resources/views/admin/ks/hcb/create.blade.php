@extends('admin.layouts.default')
@section('t1','品类热销榜banner设置')
@if(isset($show))
        @section('t2','查看')
   @elseif(isset($info))
        @section('t2','修改')
    @else
        @section('t2','新增')
@endif



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
      $("#img").fileinput({
            initialPreviewAsData: true,
            language: 'zh',
            maxFileSize: 1500,
            showUpload: false,
          showRemove: false,
          showClose: false,
            allowedFileExtensions: ["jpg", 'jpeg', "png", "gif"],
            @if(isset($info)&&!empty($info->img))
            initialPreview: ["{{$info->img}}"],
            @endif

        });
    </script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
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
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.hcb.update',$info->id) }}@else{{ route('admin.ks.hcb.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">


                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">选择分类</label>

                            <div class="col-sm-8">
                                <select name="cat_id" class="form-control">
                                    @foreach($cats as $cat)
                                        <option @if(isset($info)&&$info->cat_id==$cat->cat_id) selected @endif value="{{$cat->cat_id}}">{{$cat->cat_name}}</option>
                                        @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="img" class="col-sm-2 control-label">图片</label>
                            <div  class="col-sm-8">
                                <input id="img" name="img" type="file"  >
                                <p style="color: red;margin-top: 5px">图片建议尺寸：750*120</p>
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.hcb.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection