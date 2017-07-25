@extends('admin.layouts.default')
@section('t1','权限')
@if(isset($show))
    @section('t2','查看')
@elseif(isset($permission))
    @section('t2','修改')
@else
    @section('t2','新增')
@endif
@section('css')
    <link rel="stylesheet" href="/adminlte/plugins/iCheck/all.css">
@endsection
@section('js')
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
    </script>
    @include('admin.common.layer_tip')
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                <!-- form start -->
                    <form class="form-horizontal box-header" method="post"
                          action="@if(isset($permission)){{ route('admin.permission.update',$permission) }}@else{{ route('admin.permission.store') }}@endif">
                        {{csrf_field()}}
                        @if(isset($permission)){{method_field('PUT')}}@endif
                        @if(isset($show))<fieldset disabled>@endif
                        <div class="box-body">
                            <div class="form-group">
                                <label for="pid" class="col-sm-2 control-label">上级菜单</label>

                                <div class="col-sm-8">
                                    <select name="pid" class="form-control" id="pid">
                                        <option value="0">一级菜单</option>
                                        @foreach($permissions as $perm)
                                            <option @if(isset($permission)&&$permission->pid==$perm['id']) selected @endif value="{{$perm['id']}}">{{$perm['delimiter'].$perm['display_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="display_name" class="col-sm-2 control-label">菜单名称</label>

                                <div class="col-sm-8">
                                    <input name="display_name"
                                           value="@if(isset($permission)){{$permission->display_name}}@else{{old('display_name')}}@endif"
                                           type="text" class="form-control" id="display_name" placeholder="名称菜单"
                                           required>
                                    @if ($errors->has('display_name'))
                                        <div class="alert alert-warning">{{ $errors->first('display_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">权限标识</label>

                                <div class="col-sm-8">
                                    <input value="@if(isset($permission)){{$permission->name}}@else{{old('name')}}@endif" name="name" type="text" class="form-control" id="name" placeholder="权限标识"
                                           required>
                                    @if ($errors->has('password'))
                                        <div class="alert alert-warning">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="url" class="col-sm-2 control-label">url</label>

                                <div class="col-sm-8">
                                    <input value="@if(isset($permission)){{$permission->url}}@else{{old('url')}}@endif" name="url" type="text" class="form-control" id="url" placeholder="url"
                                           >
                                    @if ($errors->has('url'))
                                        <div class="alert alert-warning">{{ $errors->first('url') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="icon" class="col-sm-2 control-label">图标</label>

                                <div class="col-sm-8">
                                    <input value="@if(isset($permission)){{$permission->icon}}@else{{old('icon')}}@endif" name="icon" type="text" class="form-control" id="icon" placeholder="图标">
                                    @if ($errors->has('password'))
                                        <div class="alert alert-warning">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="icon" class="col-sm-2 control-label"></label>

                                <div class="col-sm-8">
                                  <a href="http://fontawesome.io/icons/">图标库</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">显示</label>

                                <div class="col-sm-8">
                                    <label>
                                        <input value="1" type="radio" name="ishow" class="minimal"  @if(isset($permission)&&$permission->ishow!=1)  @else checked @endif>是
                                    </label>
                                    <label style="margin-left: 10px">
                                        <input value="0" type="radio" name="ishow" class="minimal" @if(isset($permission)&&$permission->ishow==0) checked @endif>否
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-2 control-label">描述</label>

                                <div class="col-sm-8">
                                    <textarea name="description" id="description" class="form-control" rows="3"
                                              placeholder="描述 ...">@if(isset($permission)){{$permission->description}}@else{{old('description')}}@endif</textarea>


                                </div>
                            </div>

                        </div>
                            @if(isset($show))</fieldset>@endif
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="{{route('admin.permission.index')}}" class="btn btn-default">返回</a>
                            <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection