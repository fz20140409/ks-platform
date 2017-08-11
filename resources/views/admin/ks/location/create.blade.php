@extends('admin.layouts.default')
@section('t1','地区数据字典')
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
        //省
        $("select[name='province']").change(function () {
            var id=$("select[name='province'] option:selected").val();
            $.ajax({
                type:'POST',
                url:'{{route('admin.ks.location.getData')}}',
                data:{'id':id},
                dataType: 'json',
                success:function (result) {
                    var e='';
                    for (var i = 0; i < result.data.length; i++) {
                        e+='<option value="'+result.data[i].id+'">'+result.data[i].name+'</option>';
                    };
                    $("select[name='city'] option:gt(0)").remove();
                    $("select[name='city'] option:first").after(e);



                }
            })

        })

        //如果选择省份
        @if(isset($info))
            var id=$("select[name='province'] option:selected").val();
            $.ajax({
                type:'POST',
                url:'{{route('admin.ks.location.getData')}}',
                data:{'id':id},
                dataType: 'json',
                success:function (result) {
                    var e='';
                    for (var i = 0; i < result.data.length; i++) {
                        e+='<option value="'+result.data[i].id+'">'+result.data[i].name+'</option>';
                    };
                    $("select[name='city'] option:gt(0)").remove();
                    $("select[name='city'] option:first").after(e);

                    $("select[name='city']").find("option[value='{{$info->city}}']").attr('selected',true);

                }
        })
        @endif
    </script>
    <script>
        $(document).ready(function() {
            $('#form')
                .bootstrapValidator({
                    feedbackIcons: {
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        county: {
                            message: '区县名称不能为空',
                            validators: {
                                notEmpty: {
                                    message: '区县名称不能为空'
                                },
                                stringLength: {
                                    max: 35,
                                    message: '区县名称长度小于35字符'
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
                <form id="form" enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.location.update',$info->id) }}@else{{ route('admin.ks.location.store') }}@endif">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    @if(isset($show))<fieldset disabled>@endif
                    <div class="box-body">
                        <div class="form-group">
                            <label for="province" class="col-sm-2 control-label">省</label>

                            <div class="col-sm-8">
                                <select name="province" class="form-control">
                                    <option  value="-1">请选择省</option>
                                    @foreach($provinces as $item)
                                        <option @if(isset($info)&&$item->id==$info->province) selected @endif   value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="col-sm-2 control-label">市</label>

                            <div class="col-sm-8">
                                <select name="city" class="form-control">
                                    <option  value="-1">请选择市</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="county" class="col-sm-2 control-label">区/县</label>

                            <div class="col-sm-8">
                                <input value="@if(isset($info)){{$info->county}}@else{{old('county')}}@endif" name="county" type="text" class="form-control" id="county" placeholder="区/县" required >
                                @if ($errors->has('county'))
                                    <div class="alert alert-warning">{{ $errors->first('county') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(isset($show))</fieldset>@endif
                    <!-- /.box-body -->
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.location.index')}}" class="btn btn-default">返回</a>
                        <button @if(isset($show)) style="display: none" @endif type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                    <!-- /.box-footer -->
                </form>



            </div>
        </div>
        </div>
    </section>
    @endsection