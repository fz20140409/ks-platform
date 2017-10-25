@extends('admin.layouts.default')
@section('t1','背景及icon设置')
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
    <link rel="stylesheet" href="/plugins/bootstrapvalidator/css/bootstrapValidator.min.css">
    @endsection
@section('js')
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/piexif.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/sortable.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/purify.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/locales/zh.js"></script>
    <script src="/plugins/bootstrapvalidator/js/bootstrapValidator.js"></script>
    <script>
      $("#icon").fileinput({
            initialPreviewAsData: true,
            language: 'zh',
            maxFileSize: 1500,
            showUpload: false,
          @if(isset($info))
          showRemove: false,
          @else
          showRemove: true,
          @endif
            showClose: false,
            allowedFileExtensions: ["jpg", 'jpeg', "png", "gif"],
            @if(isset($info)&&!empty($info->bgurl))
            initialPreview: ["{{$info->bgurl}}"],
            @endif

        });
    </script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
    </script>
    <script>
        $("#type").change(function () {
            var type = $(this).val();
            switch (type) {
                case '0':
                    $('#img_size').show();
                    $('#img_size').text('建议图片宽750*高374');
                    break;
                case '1':
                    $('#img_size').hide();
                    break;
                case '2':
                    $('#img_size').show();
                    $('#img_size').text('建议图片宽750*高1206');
                    break;
            }
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
                <form id="form" enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.mb.update',$info->id) }}@else{{ route('admin.ks.mb.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">
                        @if(isset($info))<input type="hidden" name="old_type" value="{{$type}}">@endif

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">类型</label>

                            <div class="col-sm-8">
                                <select id="type" name="type" class="form-control" @if(isset($info)) disabled @endif>
                                    <option @if(isset($info)&&isset($info->type)&&$info->type==0) selected @endif value="0">店铺背景</option>
                                    <option @if(isset($info)&&isset($info->type)&&$info->type==1) selected @elseif(isset($pt)) style="display: none;" @endif value="1">平台icon</option>
                                    <option @if(isset($info) && !isset($info->type)) selected @endif value="2">名片背景</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="col-sm-2 control-label">图片</label>
                            <div  class="col-sm-8">
                                <input id="icon" name="icon" type="file" >
                                <p id="img_size" style="color: red;margin-top: 5px">建议图片宽750*高374</p>
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>

                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="javascript:history.back();" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>

            </div>
        </div>
        </div>
    </section>
@endsection