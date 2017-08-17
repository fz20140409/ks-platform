@extends('admin.layouts.default')
@section('t1','轮播图')
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
      $("#url").fileinput({
            initialPreviewAsData: true,
            language: 'zh',
            maxFileSize: 1500,
          @if(isset($info))
          showRemove: false,
          @else
          showRemove: true,
          @endif
            showUpload: false,
            allowedFileExtensions: ["jpg", "png", "gif"],
            @if(isset($info)&&!empty($info->url))
            initialPreview: ["{{$info->url}}"],
            @endif

        });
    </script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
        //内部链接
        $('#show_inner').on('ifChecked', function () {
            $('#type').val('');
            $('.inner').show();
            $('.outer').hide();
            $('.cj').show();
           $('#cj').iCheck('check');
            $('#type').val('');
            $('#type').val(2);
        });

        //外部链接
        $('#show_outer').on('ifChecked', function () {

            $('#type').val(0);
            $('#r_url').val('');
            $('.inner').hide();
            $('.outer').show();
            $('.jh').hide();
            $('.cj').hide();
            $('.tt').hide();

        });
        //机会
        $('#jh').on('ifChecked', function () {
            $('#type').val('');
            $('#type').val(4);
            $('#r_url').val('');
            $('.jh').show();
            $('.cj').hide();
            $('.tt').hide();
            $(".jh option:first").prop("selected", 'selected');

        });
        //头条
        $('#tt').on('ifChecked', function () {
            $('#type').val('');
            $('#type').val(3);
            $('#r_url').val('');
            $('.jh').hide();
            $('.cj').hide();
            $('.tt').show();
            $(".tt option:first").prop("selected", 'selected');

        });
        //厂家 默认
        $('#cj').on('ifChecked', function () {
            $('#type').val('');
            $('#type').val(2);
            $('#r_url').val('');
            $('.jh').hide();
            $('.cj').show();
            $('.tt').hide();
            $(".cj option:first").prop("selected", 'selected');

        });
        
        function set_url() {
            $('#r_url').val('');
            $('#r_url').val($('#flag3').val());
        }
        //头条下拉框
        $('.tt').change(function () {
            var id=$('.tt option:selected').val();
            $('#r_url').val('');
            $('#r_url').val(id);
        })
        //机会下拉框
        $('.jh').change(function () {
            var id=$('.jh option:selected').val();
            $('#r_url').val('');
            $('#r_url').val(id);
        })
        //厂家下拉框
        $('.cj').change(function () {
            var id=$('.cj option:selected').val();
            $('#r_url').val('');
            $('#r_url').val(id);
        })
        //厂家
        @if(isset($info)&&$info->type==2)
             $(".cj option[value='{{$info->r_url}}']").prop("selected", 'selected');
            @endif
        //头条
        @if(isset($info)&&$info->type==3)
             $(".tt option[value='{{$info->r_url}}']").prop("selected", 'selected');
        @endif
        //机会
        @if(isset($info)&&$info->type==4)
             $(".jh option[value='{{$info->r_url}}']").prop("selected", 'selected');
        @endif
    </script>
    @include('admin.common.layer_tip')
    @endsection
@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.banner.update',$info->id) }}@else{{ route('admin.ks.banner.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">标题</label>

                            <div class="col-sm-8">
                                <input name="title" value="@if(isset($info)){{$info->title}}@else{{old('title')}}@endif" type="text" class="form-control" id="title" placeholder="标题" required autofocus>
                                @if ($errors->has('title'))
                                    <div class="alert alert-warning">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="url" class="col-sm-2 control-label">轮播图</label>
                            <div  class="col-sm-8">
                                <input id="url" name="url" type="file" >
                                <p style="color: red;margin-top: 5px">建议图片宽720*高300</p>
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">跳转地址</label>
                            <div class="col-sm-8" style="margin-top: 6px">
                            <input @if(isset($info)) @if($info->type==0) checked @else   @endif @else checked @endif    name="flag" type="radio" class="minimal"  id="show_outer">外部链接
                            <input  @if(isset($info)&&$info->type!=0) checked @endif  name="flag" type="radio" class="minimal" id="show_inner">内部链接
                            </div>
                        </div>
                        <div class="form-group outer" @if(isset($info)&&$info->type!=0) style="display: none" @endif>
                            <label for="flag3" class="col-sm-2 control-label">外部链接</label>

                            <div class="col-sm-8">
                                <input  value="@if(isset($info)) {{$info->r_url}} @endif" type="text" class="form-control" id="flag3" placeholder="外部链接" onblur="set_url()">
                            </div>
                        </div>
                        <div class="form-group inner" style="display: @if(isset($info)&&$info->type!=0) block @else none  @endif">
                            <label for="inputPassword3" class="col-sm-2 control-label">APP内部跳转</label>
                            <div class="col-sm-8" style="margin-top: 6px">
                            <input @if(isset($info)) @if($info->type==2) checked @else  @endif @else checked  @endif  checked value="" name="flag2" type="radio" class="minimal" id="cj" >厂家/商家主页
                            <input @if(isset($info)) @if($info->type==4) checked @else  @endif   @endif  value="" name="flag2" type="radio" class="minimal" id="jh">合作机会
                            <input @if(isset($info)) @if($info->type==3) checked @else  @endif   @endif value="" name="flag2" type="radio" class="minimal" id="tt">优惠头条
                            </div>
                        </div>
                        <div class="form-group cj" style="display: @if(isset($info)&&$info->type==2) block @else none @endif">
                            <label  class="col-sm-2 control-label">厂家/商家主页</label>
                            <div class="col-sm-8" style="margin-top: 6px">
                                <select  class="form-control">
                                    <option>请选择厂家/商家主页</option>
                                    @foreach($cj as $v)
                                        <option value="{{$v->uid}}">{{$v->company}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group jh" style="display: @if(isset($info)&&$info->type==4) block @else none @endif">
                            <label  class="col-sm-2 control-label">合作机会</label>
                            <div class="col-sm-8" style="margin-top: 6px">
                                <select  class="form-control">
                                    <option>请选择合作机会</option>
                                    @foreach($jh as $v)
                                        <option value="{{$v->id}}">{{$v->title}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group tt" style="display: @if(isset($info)&&$info->type==3) block @else none @endif">
                            <label  class="col-sm-2 control-label">优惠头条</label>
                            <div class="col-sm-8" style="margin-top: 6px">
                                <select  class="form-control">
                                    <option>请选择优惠头条</option>
                                    @foreach($tt as $v)
                                        <option value="{{$v->hid}}">{{$v->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="type" id="type" value="@if(isset($info)) {{$info->type}}  @else 0 @endif">
                        <input type="hidden" name="r_url" id="r_url" value="@if(isset($info)) {{$info->r_url}} @endif">


                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.banner.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection