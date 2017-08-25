@extends('admin.layouts.default')
@section('t1','客服设置')
@section('t2','修改')

@section('js')
    @include('admin.common.layer_tip')
@endsection

@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <form  class="box-header form-horizontal" method="post" action="{{route('admin.ks.other.kefu_setting_update')}}">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    <div class="box-body">

                        <div class="form-group">
                            <label for="tel" class="col-sm-2 control-label">客服咨询热线</label>

                            <div class="col-sm-8">
                                <input id="tel" name="tel" class="form-control" value="{{isset($kefu->tel) ? $kefu->tel : ''}}" />
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