@extends('admin.layouts.default')
@if($level==2)
    @section('t1','二级品类')
@elseif($level==3)
    @section('t1','三级品类')
@else
    @section('t1','一级品类')
@endif



@if(isset($show))
    @section('t2','查看')
@elseif(isset($info))
    @section('t2','修改')
@else
    @section('t2','新增')
@endif



@section('css')
    <link rel="stylesheet" href="/plugins/bootstrap-fileinput/css/fileinput.min.css">
    <link rel="stylesheet" href="/plugins/bootstrapvalidator/css/bootstrapValidator.min.css">
@endsection
@section('js')
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
            allowedFileExtensions: ["jpg", "png", "gif"],
            @if(isset($info)&&!empty($info->cat_icon))
            initialPreview: ["{{$info->cat_icon}}"],
            @endif

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#layer_ce')
                .bootstrapValidator({
                    feedbackIcons: {
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        cat_name: {
                            message: '品类名称不能为空',
                            validators: {
                                notEmpty: {
                                    message: '品类名称不能为空'
                                },
                                stringLength: {
                                    max: 35,
                                    message: '品类名称长度小于35字符'
                                },
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
                    <form id="layer_ce" enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.category.update',$info->cat_id) }}@else{{ route('admin.ks.category.store') }}@endif">
                        {{csrf_field()}}
                        @if(isset($info)){{method_field('PUT')}}@endif
                        @if(isset($show))<fieldset disabled>@endif
                            <div class="box-body">


                                <div class="form-group">
                                    <label for="cat_name" class="col-sm-2 control-label">品类名称</label>

                                    <div class="col-sm-8">
                                        <input value="@if(isset($info)){{$info->cat_name}}@else{{old('cat_name')}}@endif" name="cat_name" type="text" class="form-control" id="cat_name" placeholder="品类名称" required autofocus>
                                        @if ($errors->has('cat_name'))
                                            <div class="alert alert-warning">{{ $errors->first('cat_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                @if(!($level==3))
                                <div class="form-group">
                                    <label for="icon" class="col-sm-2 control-label">图片</label>
                                    <div  class="col-sm-8">
                                        <input id="icon" name="icon" type="file"  >
                                        @if(session()->has('upload'))
                                            <div class="alert alert-error">{{session('upload')}}</div>
                                        @endif
                                    </div>
                                </div>
                                    @else
                                    <input type="hidden" name="flag" value="1">
                                    @endif

                                <input type="hidden" name="pid" value="{{$pid}}">

                            </div>
                            @if(isset($show))</fieldset>@endif
                        <div class="box-footer  ">
                            <a href="@if($pid==0) {{route('admin.ks.category.index')}} @else {{route('admin.ks.category.showSub',[$pid,'level'=>$level])}} @endif" class="btn btn-default">返回</a>
                            <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection