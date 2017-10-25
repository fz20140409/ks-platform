@extends('admin.layouts.default')
@section('t1','有话说角色')



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
            allowedFileExtensions: ["jpg", 'jpeg', "png", "gif"],
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
                        name: {
                            message: '角色名称不能为空',
                            validators: {
                                notEmpty: {
                                    message: '角色名称不能为空'
                                },
                                stringLength: {
                                    max: 35,
                                    message: '角色名称不能为空不能大于35个字符'
                                }
                            }
                        },

                    }
                })
        })
    </script>
    @include('admin.common.layer_msg')
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <form id="layer_ce" enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.tr.update',$info->cid) }}@else{{ route('admin.ks.tr.store') }}@endif">
                        {{csrf_field()}}
                        @if(isset($info)){{method_field('PUT')}}@endif
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">角色名称</label>

                                    <div class="col-sm-8">
                                        <input value="@if(isset($info)){{$info->name}}@else{{old('name')}}@endif" name="name" type="text" class="form-control" id="name" placeholder="角色名称"  >
                                        @if ($errors->has('name'))
                                            <div class="alert alert-warning">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="icon" class="col-sm-2 control-label">图片</label>
                                    <div  class="col-sm-8">
                                        <input id="icon" name="icon" type="file"  >
                                        <p id="img_size" style="color: red;margin-top: 5px">建议图片宽120*高120</p>
                                        @if(session()->has('upload'))
                                            <div class="alert alert-error">{{session('upload')}}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="uid" class="col-sm-2 control-label">绑定帐号</label>

                                    <div class="col-sm-8">
                                        <select name="uid" class="form-control">
                                            <option value="-1">请选择帐号</option>
                                            @foreach($users as $v)
                                                <option value="{{$v->id}}">{{$v->email}}</option>
                                                @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>

                        <div class="box-footer  ">
                            <a href="{{route('admin.ks.tr.index')}}" class="btn btn-default">返回</a>
                            <button type="submit" class="btn btn-primary pull-right">保存</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection