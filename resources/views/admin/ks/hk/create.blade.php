@extends('admin.layouts.default')
@section('t1','热搜关键字')
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
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.hk.update',$info->id) }}@else{{ route('admin.ks.hk.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">


                        <div class="form-group">
                            <label for="searchname" class="col-sm-2 control-label">热搜关键字</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->searchname}}@else{{old('searchname')}}@endif" name="searchname" type="text" class="form-control" id="searchname" placeholder="热搜关键字" required autofocus>
                                @if ($errors->has('searchname'))
                                    <div class="alert alert-warning">{{ $errors->first('searchname') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">是否推荐</label>
                            <div class="col-sm-8" style="margin-top: 6px">
                                <input @if(isset($info)) @if($info->is_recommend==1) checked @else   @endif @else checked @endif    name="is_recommend" value="1" type="radio" class="minimal" >是
                                <input  @if(isset($info)&&$info->is_recommend==0) checked @endif  name="is_recommend" type="radio" class="minimal"  value="0">否
                            </div>
                        </div>
                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.hk.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection