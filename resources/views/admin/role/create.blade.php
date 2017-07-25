@extends('admin.layouts.default')

@section('t1','角色')
@if(isset($show))
    @section('t2','查看')
@elseif(isset($role))
    @section('t2','修改')
@else
    @section('t2','新增')
@endif
@section('js')
    @include('admin.common.layer_tip')
    @endsection
@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <form class="box-header form-horizontal" method="post" action="@if(isset($role)){{ route('admin.role.update',$role) }}@else{{ route('admin.role.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($role)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">标识</label>

                            <div class="col-sm-8">
                                <input name="name" value="@if(isset($role)){{$role->name}}@else{{old('name')}}@endif" type="text" class="form-control" id="name" placeholder="标识" required autofocus>
                                @if ($errors->has('name'))
                                    <div class="alert alert-warning">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="display_name" class="col-sm-2 control-label">名称</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($role)){{$role->display_name}}@else{{old('display_name')}}@endif" name="display_name" type="text" class="form-control" id="display_name" placeholder="名称" required>
                                @if ($errors->has('display_name'))
                                    <div class="alert alert-warning">{{ $errors->first('display_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">描述</label>

                            <div class="col-sm-8">
                                {{--<input value="@if(isset($user)){{$user->description}}@else{{old('description')}}@endif" name="description" type="text" class="form-control" id="description" placeholder="描述" required>--}}
                                <textarea name="description" class="form-control" id="description" placeholder="描述">@if(isset($role)){{$role->description}}@else{{old('description')}}@endif</textarea>
                                @if ($errors->has('description'))
                                    <div class="alert alert-warning">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.role.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection