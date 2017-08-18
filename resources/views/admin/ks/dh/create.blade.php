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
    <link rel="stylesheet" href="/adminlte/plugins/select2/select2.min.css">
@endsection
@section('js')
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/piexif.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/sortable.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/plugins/purify.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/plugins/bootstrap-fileinput/js/locales/zh.js"></script>
    <script src="/adminlte/plugins/select2/select2.min.js"></script>
    <script src="/adminlte/plugins/select2/i18n/zh-CN.js"></script>
    <script>

        $("#icon").fileinput({
            initialPreviewAsData: true,
            showCaption: false,
            language: 'zh',
            showClose: false,
            showUpload:false,
            layoutTemplates:{
                actionUpload:'',
            },
            maxFileCount: 5,
            enctype: 'multipart/form-data',
            uploadUrl: '{{ route('admin.ks.dh.store') }}', //上传的地址
            allowedFileExtensions: ["jpg", "png", "gif"],//接收的文件后缀
            @if(isset($info)&&!empty($imgs))
            initialPreview: [
                @foreach($imgs as $img)
                    "{{$img}}",
                @endforeach
                ],
            @endif

        });

        $("#video").fileinput({
            initialPreviewAsData: true,
            showCaption: false,
            language: 'zh',
            showClose: false,
            showRemove: false,
            layoutTemplates:{
                actionUpload:'',
            },
            enctype: 'multipart/form-data',
            uploadUrl: '{{ route('admin.ks.dh.store') }}', //上传的地址
            allowedFileExtensions: ['flv', 'swf', 'mkv', 'avi', 'rm', 'rmvb', 'mpeg', 'mpg', 'ogg', 'ogv', 'mov', 'wmv', 'mp4', 'webm', 'mp3'],//接收的文件后缀
            @if(isset($info)&&!empty($video->attr_value)&&($video->video_type==2))
            initialPreview: ["{{$video->attr_value}}"],
            initialPreviewConfig: [
                {type: "video", filetype: "video/mp4", key: 1},
            ],
            @endif
        }).on('fileuploaded',
            function (event, data, id, index) {
                console.log(data)
                $('#url').val(data.response.url);
                layer.msg('上传成功');
            });
    </script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
        //是
        $('#t1').on('ifChecked', function () {
            $('#display_type').show();
        })
        //否
        $('#t2').on('ifChecked', function () {
            $('#display_type').hide();
        })
        //url
        $('#tt1').on('ifChecked', function () {
            $('#vv').hide();
            $('#video_url').show();
        })
        //本地上传
        $('#tt2').on('ifChecked', function () {
            $('#vv').show();
            $('#video_url').hide();
        })
        $(document).ready(function () {
            $("select").select2({language: "zh-CN"});
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
                    <form enctype="multipart/form-data" class="box-header form-horizontal" method="post"
                          action="@if(isset($info)){{ route('admin.ks.dh.update',$info->hid) }}@else{{ route('admin.ks.dh.store') }}@endif">
                        {{csrf_field()}}
                        @if(isset($info)){{method_field('PUT')}}@endif
                        @if(isset($show))
                            <fieldset disabled>@endif
                                <div class="box-body">


                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">标题</label>

                                        <div class="col-sm-8">
                                            <input value="@if(isset($info)){{$info->title}}@else{{old('title')}}@endif"
                                                   name="title" type="text" class="form-control" id="title"
                                                   placeholder="名称" required autofocus>
                                            @if ($errors->has('title'))
                                                <div class="alert alert-warning">{{ $errors->first('title') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cate" class="col-sm-2 control-label">分类</label>

                                        <div class="col-sm-8">
                                            <select name="cate" class="form-control">
                                                @foreach($cates  as $item)
                                                    <option @if(isset($info)&&$item->id==$cate->cid) selected
                                                            @endif value="{{$item->id}}">{{$item->catename}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    {{--<div class="form-group">
                                        <label class="col-sm-2 control-label">是否置顶</label>
                                        <div class="col-sm-8" style="margin-top: 6px">
                                            <input @if(isset($info)&&$info->is_top==1) checked @endif   name="is_top"
                                                   type="radio" class="minimal" value="1">是
                                            <input @if(isset($info)) @if($info->is_top==0) checked
                                                   @else   @endif @else checked @endif    name="is_top" type="radio"
                                                   class="minimal" value="0">否
                                        </div>
                                    </div>--}}
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否添加商品</label>
                                        <div class="col-sm-8" style="margin-top: 6px">
                                            <input @if(isset($info)) @if($info->has_good==1) checked
                                                   @else   @endif @else checked @endif    name="has_good" type="radio"
                                                   class="minimal" value="1" id="t1">是
                                            <input @if(isset($info)&&$info->has_good==0) checked @endif  name="has_good"
                                                   type="radio" class="minimal" value="0" id="t2">否
                                        </div>
                                    </div>
                                    <div class="form-group" id="display_type"
                                         @if(isset($info)&&$info->has_good==0) style="display: none" @endif>
                                        <label class="col-sm-2 control-label">商品显示方式</label>
                                        <div class="col-sm-8" style="margin-top: 6px">
                                            <input @if(isset($info)) @if($info->display_type==0) checked
                                                   @else   @endif @else checked @endif    name="display_type"
                                                   type="radio" class="minimal" value="0">列表
                                            <input @if(isset($info)&&$info->display_type==1) checked
                                                   @endif  name="display_type" type="radio" class="minimal" value="1">方格
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="intro" class="col-sm-2 control-label">优惠信息</label>

                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="intro"
                                                      required>@if(isset($info)) {{$info->intro}} @endif</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="icon" class="col-sm-2 control-label">图片</label>
                                        <div class="col-sm-8">
                                            <input id="icon" name="icon[]" type="file" multiple class="file-loading">
                                            @if(session()->has('upload'))
                                                <div class="alert alert-error">{{session('upload')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group" id="video_type">
                                        <label class="col-sm-2 control-label">上传视频方式</label>
                                        <div class="col-sm-8" style="margin-top: 6px">
                                            <input  @if(isset($info)) @if($video->video_type==2) checked
                                                    @else   @endif @else checked @endif   name="video_type"
                                                   type="radio" class="minimal" value="2" id="tt2">本地上传
                                            <input @if(isset($info)&&$video->video_type==1) checked
                                                   @endif name="video_type" type="radio" class="minimal" value="1" id="tt1">url地址
                                        </div>
                                    </div>
                                    <div id="vv" class="form-group" @if(isset($info)&&$video->video_type!=2) style="display: none" @endif>
                                        <label  class="col-sm-2 control-label">视频</label>

                                        <div class="col-sm-8">
                                            <input  name="video" type="file" id="video">
                                            <input type="hidden" name="url" id="url">
                                            @if(session()->has('video'))
                                                <div class="alert alert-error">{{session('video')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group" id="video_url"   style="display: @if(isset($info)&&$video->video_type==1) show  @endif none">
                                        <label  class="col-sm-2 control-label">视频地址</label>

                                        <div class="col-sm-8">
                                            <input value="@if(isset($info)&&$video->video_type==1){{$video->attr_value}}@else{{old('video_url')}}@endif"
                                                   name="video_url" type="text" class="form-control">
                                            @if ($errors->has('video_url'))
                                                <div class="alert alert-warning">{{ $errors->first('video_url') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="keyword" class="col-sm-2 control-label">关键字</label>

                                        <div class="col-sm-8">
                                            <input value="@if(isset($info)){{$info->keyword}}@else{{old('keyword')}}@endif"
                                                   name="keyword" type="text" class="form-control" id="keyword">
                                            @if ($errors->has('keyword'))
                                                <div class="alert alert-warning">{{ $errors->first('keyword') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="area" class="col-sm-2 control-label">发布范围</label>

                                        <div class="col-sm-8">
                                            <select name="area[]" class="form-control select2" multiple="multiple"
                                                    data-placeholder="请选择" style="width: 100%;">
                                                @foreach($areas as $item)
                                                    <option @if(isset($info)) @if(in_array($item->id,$area_arr)) selected
                                                            @endif @endif  value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                        <div class="box-footer  ">
                            <a href="{{route('admin.ks.dh.index')}}" class="btn btn-default">返回</a>
                            <button @if(isset($show)) style="display: none" @endif type="submit"
                                    class="btn btn-primary pull-right">保存
                            </button>
                        </div>
                        <!-- /.box-footer -->
                    </form>


                </div>
            </div>
        </div>
    </section>
@endsection