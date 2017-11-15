@extends('admin.layouts.default')
@section('t1','个人中心')
@section('t2','修改')



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
      $("#avatar").fileinput({
            initialPreviewAsData: true,
            language: 'zh',
            maxFileSize: 1500,
            showRemove: false,
            showUpload: false,
            allowedFileExtensions: ["jpg", 'jpeg', "png", "gif"],
            initialPreview: ["@if(isset($user)&&!empty($user->avatar)){{$user->avatar}}@else /img/default_avatar_male.jpg @endif"],

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
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="{{ route('admin.ks.other.do_user_center') }}">
                    {{csrf_field()}}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">邮箱</label>

                            <div class="col-sm-8">
                                <input name="email" value="@if(isset($user)){{$user->email}}@else{{old('email')}}@endif" type="email" class="form-control" id="email" placeholder="邮箱" required readonly>
                                @if ($errors->has('email'))
                                    <div class="alert alert-warning">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">原密码</label>

                            <div class="col-sm-8">
                                <input name="raw_password" type="password" class="form-control" id="raw_password" placeholder="原密码" @if(!isset($user)) required @endif>
                                @if ($errors->has('raw_password'))
                                    <div class="alert alert-warning">{{ $errors->first('raw_password') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">密码</label>

                            <div class="col-sm-8">
                                <input name="password" type="password" class="form-control" id="password" placeholder="密码" @if(!isset($user)) required @endif>
                                @if ($errors->has('password'))
                                    <div class="alert alert-warning">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-sm-2 control-label">确认密码</label>

                            <div class="col-sm-8">
                                <input name="password_confirmation"   type="password" class="form-control" id="password-confirm" placeholder="确认密码"  @if(!isset($user)) required @endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">昵称</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($user)){{$user->name}}@else{{old('name')}}@endif" name="name" type="text" class="form-control" id="name" placeholder="昵称" required>
                                @if ($errors->has('name'))
                                    <div class="alert alert-warning">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">头像</label>
                            <div  class="col-sm-8">
                                <input id="avatar" name="avatar" type="file" >
                                @if(session()->has('upload'))
                                    <div class="alert alert-error">{{session('upload')}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.home')}}" class="btn btn-default">返回</a>
                        <button  type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection