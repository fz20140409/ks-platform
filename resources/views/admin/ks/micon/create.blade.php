@extends('admin.layouts.default')
@section('t1','与我有关-更多图标设置')
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
            @if(isset($info)&&!empty($info->icon))
            initialPreview: ["{{$info->icon}}"],
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
        $(document).ready(function() {
            $('#form')
                .bootstrapValidator({
                    feedbackIcons: {
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        name: {
                            message: '名称不能为空',
                            validators: {
                                notEmpty: {
                                    message: '名称不能为空'
                                },
                                stringLength: {
                                    max: 35,
                                    message: '名称长度小于35字符'
                                },
                            }
                        },
                        m_url: {
                            message: '链接不能为空',
                            validators: {
                                notEmpty: {
                                    message: '链接不能为空'
                                },
                                stringLength: {
                                    max: 200,
                                    message: '名称长度小于200字符'
                                },
                                uri: {
                                    allowLocal: true,
                                    message: '输入必须是url'
                                }
                            }
                        },

                    }
                })
        })
    </script>
    @include('admin.common.layer_tip')
    @endsection
@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <form id="form" enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.micon.update',$info->id) }}@else{{ route('admin.ks.micon.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">


                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">名称</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->name}}@else{{old('name')}}@endif" name="name" type="text" class="form-control" id="name" placeholder="名称" required autofocus>
                                @if ($errors->has('name'))
                                    <div class="alert alert-warning">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="col-sm-2 control-label">图标</label>
                            <div  class="col-sm-8">
                                <input id="icon" name="icon" type="file"  >
                                <p style="color: red;margin-top: 5px">建议图片宽120*高120</p>
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group" @if(isset($info)) hidden="hidden" @endif>
                            <label for="name" class="col-sm-2 control-label">类型</label>

                            <div class="col-sm-8">
                               <select name="type" class="form-control">
                                   <option @if(isset($info)&&$info->type==1) selected @endif value="1">常用功能</option>
                                   <option @if(isset($info)&&$info->type==2) selected @endif value="2">网店功能</option>
                                   <option @if(isset($info)&&$info->type==3) selected @endif value="3">生产贸易商功能</option>
                               </select>
                            </div>
                        </div>

                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.micon.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection