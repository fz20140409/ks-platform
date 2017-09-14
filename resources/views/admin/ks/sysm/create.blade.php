@extends('admin.layouts.default')
@section('t1','系统消息')
@if(isset($show))
        @section('t2','查看')
   @elseif(isset($info))
        @section('t2','修改')
    @else
        @section('t2','新增')
@endif



@section('css')
    <link rel="stylesheet" href="/adminlte/plugins/iCheck/all.css">

    <link rel="stylesheet" href="/plugins/bootstrapvalidator/css/bootstrapValidator.min.css">
    <link rel="stylesheet" href="/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    @endsection
@section('js')
    <script type="text/javascript" charset="utf-8" src="/plugins/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/plugins/ueditor/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>

    <script src="/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script src="/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    <script src="/plugins/bootstrapvalidator/js/bootstrapValidator.js"></script>
    <script src="/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
        /*var ue = UE.getEditor('editor');*/
    $('#editor').wysihtml5();
        $(".form_datetime").datetimepicker({
            format: "yyyy-mm-dd hh:ii:ss",
            language: 'zh-CN',//显示中文
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
                        title: {
                            message: '消息标题不能为空',
                            validators: {
                                notEmpty: {
                                    message: '消息标题不能为空'
                                },
                                stringLength: {
                                    max: 35,
                                    message: '消息标题长度小于35字符'
                                },
                            }
                        },
                        intro: {
                            message: '消息简介不能为空',
                            validators: {
                                notEmpty: {
                                    message: '消息简介不能为空'
                                },
                                stringLength: {
                                    max: 35,
                                    message: '消息简介长度小于35字符'
                                },
                            }
                        },
                        content: {
                            message: '消息内容不能为空',
                            validators: {
                                notEmpty: {
                                    message: '消息内容不能为空'
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
                <form id="form" enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.sysm.update',$info->id) }}@else{{ route('admin.ks.sysm.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">


                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">消息标题</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->title}}@else{{old('title')}}@endif" name="title" type="text" class="form-control" id="title" placeholder="消息标题" required autofocus>
                                @if ($errors->has('title'))
                                    <div class="alert alert-warning">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="intro" class="col-sm-2 control-label">消息简介</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->intro}}@else{{old('intro')}}@endif" name="intro" type="text" class="form-control" id="intro" placeholder="消息简介" required>
                                @if ($errors->has('intro'))
                                    <div class="alert alert-warning">{{ $errors->first('intro') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content" class="col-sm-2 control-label">消息内容</label>

                            <div class="col-sm-8">
                                <textarea id="content" name="content" style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" id="editor">@if(isset($info)){{$info->content}}@else{{old('content')}}@endif</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_sync" class="col-sm-2 control-label">新增并同步推送</label>

                            <div class="col-sm-8">
                               <input @if(isset($info)) @if($info->is_sync==1) checked @else  @endif @else checked  @endif  value="1" name="is_sync" type="radio" class="minimal" >是
                               <input @if(isset($info)&&$info->is_sync==0) checked  @endif value="0" name="is_sync" type="radio" class="minimal">否
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label for="fb_time" class="col-sm-2 control-label">发布时间</label>--}}

                            {{--<div class="col-sm-8">--}}

                                {{--<div class="input-append date form_datetime">--}}
                                    {{--<input  name="fb_time"  type="text" value="@if(isset($info)){{$info->fb_time}}@else{{old('fb_time')}}@endif"  class="form-control">--}}
                                    {{--<span class="add-on"><i class="icon-th"></i></span>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</div>--}}




                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.sysm.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection