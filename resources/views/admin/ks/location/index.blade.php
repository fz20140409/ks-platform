@extends('admin.layouts.default')
@section('t1','地区数据字典')
@section('t2','列表')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--box-header-->
                    <div class="box-header">
                        <div class="row">
                            <form class="form-inline" action="{{route('admin.ks.location.index')}}">
                                <div class="col-lg-1 col-xs-3">
                                    <select name="page_size" class="form-control">
                                        @foreach($page_sizes as $k=> $v)
                                            <option @if($page_size==$k) selected @endif value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-9 col-xs-10">
                                    省
                                    <select name="province" class="form-control">
                                        <option  value="-1">全部</option>
                                        @foreach($provinces as $item)
                                            <option @if($province==$item->id) selected @endif  value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach

                                    </select>
                                    市
                                    <select name="city" class="form-control">
                                        <option  value="-1">全部</option>

                                    </select>
                                    {{--区/县
                                    <select name="county" class="form-control">
                                        <option  value="-1">全部</option>

                                    </select>--}}
                                    <button class="btn btn-default" type="submit">查询</button>

                                </div>
                            </form>
                            @if(Auth::user()->can('admin.ks.location.create'))
                                <div class="col-lg-2 col-xs-2 pull-right">
                                    <a href="{{route('admin.ks.location.create')}}" class="btn btn-primary">新增</a>
                                </div>
                            @endif

                        </div>
                    </div>
                    <!--box-header-->
                    <!--box-body-->
                    <form id="ids">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>省</th>
                                    <th>市</th>
                                    <th>区/县</th>

                                    <th>操作</th>
                                </tr>
                                @foreach($infos as $k=>$info)
                                    <tr>
                                        <th><input class="minimal" name="ids[]" type="checkbox"
                                                   value="{{$info->id}}"></th>
                                        <td>{{$k+1+($infos->currentPage() -1)*$infos->perPage()}}</td>

                                        <td>{{$info->province}}</td>
                                        <td>{{$info->city}}</td>
                                        <td>{{$info->county}}</td>
                                        <td>
                                            <a class=" op_edit"  href="{{route('admin.ks.location.edit',$info->id)}}"
                                               style="margin-right: 10px;display: none">
                                                <i class="fa fa-pencil-square-o " aria-hidden="true">修改</i></a>
                                            <a style="display: none"  class=" op_destroy"  href="javascript:del('{{route('admin.ks.location.destroy',$info->id)}}')">
                                                <i class="fa  fa-trash-o " aria-hidden="true">删除</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </form>
                    <!--box-body-->
                    <!--box-footer-->
                    <div class="box-footer ">
                        <div style="float: right">
                            {{$infos->appends($link_where)->links()}}
                        </div>
                    </div>
                    <!--box-footer-->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('css')
    <link rel="stylesheet" href="/adminlte/plugins/iCheck/all.css">
@endsection

@section('js')
    <script src="/plugins/layer/layer.js"></script>
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script>
        $('input[type="checkbox"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
    </script>
    <script>
        //屏蔽和显示
        @if(Auth::user()->can('admin.ks.location.updateStatus'))
             $(".op_show").show();
        @endif
        //有修改权限，显示修改
        @if(Auth::user()->can('admin.ks.location.edit'))
            $(".op_edit").show();
        @endif
        //有删除权限，显示删除
        @if(Auth::user()->can('admin.ks.location.destroy'))
            $(".op_destroy").show();
        @endif
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
        @if($province!=-1)
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
                    //如果选择市
                    @if($city!=-1)
                     var va=$("select[name='city']").find("option[value='{{$city}}']").attr('selected',true);
                    @endif
                }
            })
            @endif




    </script>
    @include('admin.common.layer_del')
@endsection