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
    @endsection
@section('js')
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/piexif.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/sortable.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/purify.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/locales/zh.js"></script>
    <script>
      $("#icon").fileinput({
            initialPreviewAsData: true,
            language: 'zh',
            maxFileSize: 1500,
            showUpload: false,
            allowedFileExtensions: ["jpg", "png", "gif"],
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
    @include('admin.common.layer_tip')
    @endsection
@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.menu.update',$info->id) }}@else{{ route('admin.ks.menu.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">

                        <div class="form-group">
                            <label for="icon" class="col-sm-2 control-label">图标</label>
                            <div  class="col-sm-8">
                                <input id="icon" name="icon" type="file"  >
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">品牌名称</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->menu_name}}@else{{old('menu_name')}}@endif" name="menu_name" type="text" class="form-control" id="menu_name" placeholder="名称" required >
                                @if ($errors->has('menu_name'))
                                    <div class="alert alert-warning">{{ $errors->first('menu_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="m_url" class="col-sm-2 control-label">所属品类</label>

                            <div class="col-sm-8">
                                <div style="background:#ecf0f5;padding: 10px 0px;">
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                </div>
                                <div style="margin: 10px 0px">
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                </div>
                                <div style="background:#ecf0f5;padding: 10px 0px;">
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                </div>
                                <div style="margin: 10px 0px">
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                    <input class="minimal" name="user_ids[]" type="checkbox"
                                           value="">冷冻肉类
                                </div>



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