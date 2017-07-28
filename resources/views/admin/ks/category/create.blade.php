<form class="box-header form-horizontal" method="post" action="@if(isset($info)){{ route('admin.ks.category.update',$info->cat_id) }}@else{{ route('admin.ks.category.store') }}@endif">
    {{csrf_field()}}
    @if(isset($info)){{method_field('PUT')}}@endif
        <div class="box-body">
            <div class="form-group">
                <label for="cat_name" class="col-sm-3 control-label">品类名称</label>

                <div class="col-sm-8">
                    <input value="@if(isset($info)){{$info->cat_name}}@else{{old('cat_name')}}@endif" name="cat_name" type="text" class="form-control" id="cat_name" placeholder="品类名称" required>
                    @if ($errors->has('cat_name'))
                        <div class="alert alert-warning">{{ $errors->first('cat_name') }}</div>
                    @endif
                </div>
            </div>

        </div>
    <div class="box-footer  ">
        <a href="" class="btn btn-default">返回</a>
        <button  type="submit" class="btn btn-primary pull-right">保存</button>
    </div>
</form>