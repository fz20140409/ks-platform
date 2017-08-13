@extends('admin.layouts.default')
@section('t1','优化原因')
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
    @endsection
@section('js')
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/plugins/bootstrapvalidator/js/bootstrapValidator.js"></script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
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
                        r_name: {
                            message: '原因名称不能为空',
                            validators: {
                                notEmpty: {
                                    message: '原因名称不能为空'
                                },
                                stringLength: {
                                    max: 35,
                                    message: '原因名称长度小于35字符'
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
                <form id="form" enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.dhor.update',$info->r_id) }}@else{{ route('admin.ks.dhor.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">


                        <div class="form-group">
                            <label for="r_name" class="col-sm-2 control-label">原因名称</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->r_name}}@else{{old('r_name')}}@endif" name="r_name" type="text" class="form-control" id="r_name" placeholder="名称" required >
                                @if ($errors->has('r_name'))
                                    <div class="alert alert-warning">{{ $errors->first('r_name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.dhor.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection