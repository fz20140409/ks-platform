@extends('admin.layouts.default')
@section('t1','优惠头条')
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
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.dh.update',$info->id) }}@else{{ route('admin.ks.dh.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">


                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">标题</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->menu_name}}@else{{old('menu_name')}}@endif" name="menu_name" type="text" class="form-control" id="menu_name" placeholder="名称" required autofocus>
                                @if ($errors->has('menu_name'))
                                    <div class="alert alert-warning">{{ $errors->first('menu_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">分类</label>

                            <div class="col-sm-8">
                                <select name="" class="form-control">
                                    <option>全部</option>
                                    <option>推荐</option>
                                    <option>未推荐</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">是否置顶</label>
                            <div class="col-sm-8" style="margin-top: 6px">
                                <input @if(isset($info)) @if($info->type==0) checked @else   @endif @else checked @endif    name="flag" type="radio" class="minimal"  id="show_outer">是
                                <input  @if(isset($info)&&$info->type!=0) checked @endif  name="flag" type="radio" class="minimal" id="show_inner">否
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">是否添加商品</label>
                            <div class="col-sm-8" style="margin-top: 6px">
                                <input @if(isset($info)) @if($info->type==0) checked @else   @endif @else checked @endif    name="flag" type="radio" class="minimal"  id="show_outer">是
                                <input  @if(isset($info)&&$info->type!=0) checked @endif  name="flag" type="radio" class="minimal" id="show_inner">否
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">商品显示方式</label>
                            <div class="col-sm-8" style="margin-top: 6px">
                                <input @if(isset($info)) @if($info->type==0) checked @else   @endif @else checked @endif    name="flag" type="radio" class="minimal"  id="show_outer">列表
                                <input  @if(isset($info)&&$info->type!=0) checked @endif  name="flag" type="radio" class="minimal" id="show_inner">方格
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">优惠信息</label>

                            <div class="col-sm-8">
                                <textarea class="form-control"></textarea>
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
                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">视频</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->menu_name}}@else{{old('menu_name')}}@endif" name="menu_name" type="text" class="form-control" id="menu_name" placeholder="名称" required autofocus>
                                @if ($errors->has('menu_name'))
                                    <div class="alert alert-warning">{{ $errors->first('menu_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">关键字</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->menu_name}}@else{{old('menu_name')}}@endif" name="menu_name" type="text" class="form-control" id="menu_name" placeholder="名称" required autofocus>
                                @if ($errors->has('menu_name'))
                                    <div class="alert alert-warning">{{ $errors->first('menu_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">发布范围</label>

                            <div class="col-sm-8">
                                <select name="" class="form-control">
                                    <option>全国</option>
                                    <option>推荐</option>
                                    <option>未推荐</option>
                                </select>
                            </div>
                        </div>




                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.dh.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection