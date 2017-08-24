@extends('admin.layouts.default')
@section('t1',"$info->type_name")
@section('t2','详情')
@section('css')
    <style>
        .box-body table tr td:first-child {
            color: #00a7d0;
        }
    </style>
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <!-- form start -->
                    <div class="box-header">
                        <ul class="nav nav-tabs">

                            <li role="presentation" class="active"><a href="javascript:getInfo(1)">个人基础资料</a></li>
                            <li role="presentation"><a href="javascript:getInfo(2)">老板信息</a></li>
                            @if($info->utype!=3)
                                <li role="presentation"><a href="javascript:getInfo(3)">企业信息</a></li>
                                <li role="presentation"><a href="javascript:getInfo(4)">业务信息</a></li>
                                <li role="presentation"><a href="javascript:getInfo(5)">实际经营人</a></li>
                            @endif
                        </ul>
                    </div>
                    <div id="box-body" class=" box-body table-responsive no-padding">

                    </div>
                    <div class="box-footer  ">
                        <a href="{{route('admin.ks.um.index')}}" class="btn btn-default">返回</a>

                    </div>


                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        $(function () {
            $("#box-body").load("{{route('admin.ks.um.getPersonInfo',$info->uid)}}");
        });
        function getInfo(id) {
            switch (id) {
                case 1:
                    //个人基础资料
                    $(".nav-tabs li").removeClass('active');
                    $(".nav-tabs li").eq(0).addClass('active');
                    $("#box-body").empty();
                    $("#box-body").load("{{route('admin.ks.um.getPersonInfo',$info->uid)}}");
                    break;
                case 2:
                    //老板信息
                    $(".nav-tabs li").removeClass('active');
                    $(".nav-tabs li").eq(1).addClass('active');
                    $("#box-body").empty();
                    $("#box-body").load("{{route('admin.ks.um.getBossInfo',$info->uid)}}");
                    break;
                case 3:
                    //企业信息
                    $(".nav-tabs li").removeClass('active');
                    $(".nav-tabs li").eq(2).addClass('active');
                    $("#box-body").empty();
                    $("#box-body").load("{{route('admin.ks.um.getCompanyInfo',$info->uid)}}");
                    break;
                case 4:
                    //业务信息
                    $(".nav-tabs li").removeClass('active');
                    $(".nav-tabs li").eq(3).addClass('active');
                    $("#box-body").empty();
                    $("#box-body").load("{{route('admin.ks.um.getBusinessInfo',$info->uid)}}");
                    break;
                case 5:
                    //实际经营人
                    $(".nav-tabs li").removeClass('active');
                    $(".nav-tabs li").eq(4).addClass('active');
                    $("#box-body").empty();
                    $("#box-body").load("{{route('admin.ks.um.getTransactorInfo',$info->uid)}}");
                    break;
            }

        }
    </script>
@endsection


