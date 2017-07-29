@extends('admin.layouts.default')
@section('t1','网店')
@section('t2','详情')
    @section('css')
       <style>
           .box-body table tr td:first-child{
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

                </div>
                <div class=" box-body table-responsive no-padding">
                    <table class=" table table-hover table-bordered">
                        <tr>
                            <td width="20%">头像</td>
                            <td width="80%"><img src=""  class="img-rounded"></td>
                        </tr>
                        <tr>
                            <td>店铺名称</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>企业类型</td>
                            <td>生产商或大型贸易商</td>
                        </tr>
                        <tr>
                            <td>认证信息</td>
                            <td>已认证</td>
                        </tr>
                        <tr>
                            <td>诚信值</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>在乎数</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>优质厂商</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>商品数</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>订单数</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>评价</td>
                            <td>xxx</td>
                        </tr>
                    </table>
                </div>
                <div class="box-footer  ">
                    <a href="{{route('admin.ks.user_info.index')}}" class="btn btn-default">返回</a>

                </div>


            </div>
        </div>
        </div>
    </section>
    @endsection