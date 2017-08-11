@extends('admin.layouts.default')
@section('t1','模块')
@section('t2','设置')
@section('css')
    <link rel="stylesheet" href="/adminlte/plugins/iCheck/all.css">
    <style>
        .tt{
            margin-top: 5px
        }
    </style>
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
            <div class="box box-default">
                <!-- form start -->
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="{{route('admin.ks.other.do_module_settings')}}">
                    {{csrf_field()}}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="sp" class="col-sm-2 control-label">优惠头条</label>
                            <div  class="col-sm-8 tt " >
                                <input @if($yhtt->enabled==1) checked @endif value="1"  name="yhtt"  type="radio"  class="minimal">开启
                                <input  @if($yhtt->enabled==0) checked @endif value="0" name="yhtt" type="radio"  class="minimal">关闭
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sp" class="col-sm-2 control-label">合作机会</label>
                            <div  class="col-sm-8 tt">
                                <input @if($hzjh->enabled==1) checked @endif value="1"  name="hzjh"  type="radio"  class="minimal">开启
                                <input  @if($hzjh->enabled==0) checked @endif value="0" name="hzjh" type="radio"  class="minimal">关闭
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sp" class="col-sm-2 control-label">优质厂商</label>
                            <div  class="col-sm-8 tt">
                                <input @if($yzcs->enabled==1) checked @endif value="1"  name="yzcs"  type="radio"  class="minimal">开启
                                <input  @if($yzcs->enabled==0) checked @endif value="0" name="yzcs" type="radio"  class="minimal">关闭
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sp" class="col-sm-2 control-label">热门商品</label>
                            <div  class="col-sm-8 tt">
                                <input @if($rmsp->enabled==1) checked @endif value="1"  name="rmsp"  type="radio"  class="minimal">开启
                                <input  @if($rmsp->enabled==0) checked @endif value="0" name="rmsp" type="radio"  class="minimal">关闭
                            </div>
                        </div>
                    </div>
                    <div class="box-footer  ">
                        <button  type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>

                </form>

            </div>
        </div>
        </div>
    </section>
    @endsection