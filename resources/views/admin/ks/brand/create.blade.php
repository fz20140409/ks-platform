@extends('admin.layouts.default')
@section('t1','品牌')
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
            allowedFileExtensions: ["jpg", "png", "gif"],
            @if(isset($info)&&!empty($info->bicon))
            initialPreview: ["{{$info->bicon}}"],
            @endif

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
                        zybrand: {
                            message: '品牌名称不能为空',
                            validators: {
                                notEmpty: {
                                    message: '品牌名称不能为空'
                                },
                                stringLength: {
                                    max: 35,
                                    message: '品牌名称长度小于35字符'
                                },
                            }
                        },

                    }
                })
        })
    </script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
        $('.cat_p').each(function () {
            $(this).on('ifChecked', function () {
                $(this).next('.cat_s').find('input[type="checkbox"]').iCheck('check');
            });
            $(this).on('ifUnchecked', function () {

                $(this).next('.cat_s').find('input[type="checkbox"]').iCheck('uncheck');
            });
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
                <form id="form" enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.brand.update',$info->bid) }}@else{{ route('admin.ks.brand.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">

                        <div class="form-group">
                            <label for="icon" class="col-sm-2 control-label">图标</label>
                            <div  class="col-sm-8">
                                <input id="icon" name="icon" type="file"  >
                                <p style="color: red;margin-top: 5px">建议图片宽80*高80</p>
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="zybrand" class="col-sm-2 control-label">品牌名称</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->zybrand}}@else{{old('zybrand')}}@endif" name="zybrand" type="text" class="form-control" id="zybrand" placeholder="名称" required >
                                @if ($errors->has('zybrand'))
                                    <div class="alert alert-warning">{{ $errors->first('zybrand') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="m_url" class="col-sm-2 control-label">所属品类</label>

                            <div class="col-sm-8">
                                @foreach($infos as $one)
                                    <div class="cat_p" style="background:#ecf0f5;padding: 10px 0px;margin-bottom: 10px">
                                        <input @if(!empty($cat_ids)&&in_array($one['id'],$cat_ids)) checked @endif class="minimal" name="ids[]" type="checkbox"
                                               value="{{$one['id']}}">{{$one['cat_name']}}
                                    </div>
                                    @if(!empty($one['child']))
                                        <div class="cat_s" style="margin: 10px 0px">
                                            @foreach($one['child'] as $item)
                                            <input @if(!empty($cat_ids)&&in_array($item['id'],$cat_ids)) checked @endif class="minimal" name="ids[]" type="checkbox"
                                                   value="{{$item['id']}}">{{$item['cat_name']}}
                                                @endforeach
                                        </div>
                                        @endif
                                    @endforeach
                            </div>
                        </div>
                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.brand.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection