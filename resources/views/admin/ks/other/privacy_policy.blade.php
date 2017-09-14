@extends('admin.layouts.default')
@section('t1','隐私声明')
@section('t2','修改')
@section('css')
    <link rel="stylesheet" href="/plugins/bootstrap-fileinput/css/fileinput.min.css">
    <link rel="stylesheet" href="/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    @endsection
@section('js')
    <script type="text/javascript" charset="utf-8" src="/plugins/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/plugins/ueditor/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
    <script src="/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

    <script>
      /*  var ue = UE.getEditor('editor');*/
      $('#editor').wysihtml5();
    </script>
    @include('admin.common.layer_tip')
    @endsection
@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <form enctype="multipart/form-data" class="box-header form-horizontal" method="post" action="{{route('admin.ks.other.privacy_policy_update')}}">
                    {{csrf_field()}}
                    @if(isset($info)){{method_field('PUT')}}@endif
                    <div class="box-body">

                        <div class="form-group">
                            <label for="menu_name" class="col-sm-2 control-label">隐私声明</label>

                            <div class="col-sm-8">
                                <textarea name="privacy_policy" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" id="editor">{{$content}}</textarea>
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