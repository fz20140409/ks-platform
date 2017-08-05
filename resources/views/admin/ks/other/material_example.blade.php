@extends('admin.layouts.default')
@section('t1','上传材料范例')
@section('t2','修改')
@section('css')
    <link rel="stylesheet" href="/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/plugins/bootstrap-fileinput/css/fileinput.min.css">
    @endsection
@section('js')
    <script type="text/javascript" charset="utf-8" src="/plugins/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/plugins/ueditor/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/piexif.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/sortable.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/purify.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/locales/zh.js"></script>
    <script>
      $("#yy").fileinput({
            initialPreviewAsData: true,
            language: 'zh',
            maxFileSize: 1500,
            showUpload: false,
          showRemove: false,
          showClose: false,
            allowedFileExtensions: ["jpg", "png", "gif"],
            @if(!empty($yy->fileurl))
            initialPreview: ["{{$yy->fileurl}}"],
            @endif

        });
      $("#sc").fileinput({
          initialPreviewAsData: true,
          language: 'zh',
          maxFileSize: 1500,
          showUpload: false,
          showRemove: false,
          showClose: false,
          allowedFileExtensions: ["jpg", "png", "gif"],
          @if(!empty($sc->fileurl))
          initialPreview: ["{{$sc->fileurl}}"],
          @endif

      });
      $("#sp").fileinput({
          initialPreviewAsData: true,
          language: 'zh',
          maxFileSize: 1500,
          showUpload: false,
          showRemove: false,
          showClose: false,
          allowedFileExtensions: ["jpg", "png", "gif"],
          @if(!empty($sp->fileurl))
          initialPreview: ["{{$sp->fileurl}}"],
          @endif

      });
    </script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
        var ue = UE.getEditor('editor');
    </script>
    @include('admin.common.layer_tip')
    @endsection
@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="{{route('admin.ks.other.material_example_update')}}">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    <div class="box-body">

                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">文字说明</label>

                            <div class="col-sm-8">
                                <textarea name="remark" style="height: 400px" id="editor">{{$yy->remark}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="yy" class="col-sm-2 control-label">营业执照范例</label>
                            <div  class="col-sm-8">
                                <input id="yy" name="yy" type="file"  >
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sc" class="col-sm-2 control-label">生产许可证</label>
                            <div  class="col-sm-8">
                                <input id="sc" name="sc" type="file"  >
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sp" class="col-sm-2 control-label">食品流通许可证</label>
                            <div  class="col-sm-8">
                                <input id="sp" name="sp" type="file"  >
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer  ">
                        <button  type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>

                </form>

            </div>
        </div>
        </div>
    </section>
    @endsection