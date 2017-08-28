@extends('admin.layouts.default')
@if($level==2)
    @section('t1','二级分类图标')
@elseif($level==3)
    @section('t1','三级分类图标')
@else
    @section('t1','一级分类图标')
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
            @if(isset($info)&&!empty($info->cicon))
            initialPreview: ["{{$info->cicon}}"],
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
                        cname: {
                            message: '分类图标名称不能为空',
                            validators: {
                                notEmpty: {
                                    message: '分类图标名称不能为空'
                                },
                                stringLength: {
                                    max: 35,
                                    message: '分类图标名称长度小于35字符'
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
                    <form id="layer_ce" enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.ci.update',$info->cid) }}@else{{ route('admin.ks.ci.store') }}@endif">
                        {{csrf_field()}}
                        @if(isset($info)){{method_field('PUT')}}@endif
                        @if(isset($show))<fieldset disabled>@endif
                            <div class="box-body">


                                <div class="form-group">
                                    <label for="cname" class="col-sm-2 control-label">分类图标名称</label>

                                    <div class="col-sm-8">
                                        <input value="@if(isset($info)){{$info->cname}}@else{{old('cname')}}@endif" name="cname" type="text" class="form-control" id="cname" placeholder="分类图标名称" required autofocus>
                                        @if ($errors->has('cname'))
                                            <div class="alert alert-warning">{{ $errors->first('cname') }}</div>
                                        @endif
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

                                <input type="hidden" name="pid" value="{{$pid}}">

                            </div>
                            @if(isset($show))</fieldset>@endif
                        <div class="box-footer  ">
                            <a href="@if($pid==0) {{route('admin.ks.ci.index')}} @else {{route('admin.ks.ci.showSub',[$pid,'level'=>$level])}} @endif" class="btn btn-default">返回</a>
                            <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection