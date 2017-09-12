<table class=" table table-hover table-bordered">
    <tr>
        <td width="20%">头像</td>
        <td><img style="float: left" width="10%"  class="img-responsive center-block" src="{{$info->uicon}}"></td>
    </tr>
    <tr>
        <td>用户类型</td>
        <td>{{$info->type_name}}</td>
    </tr>
    <tr>
        <td>真实姓名</td>
        <td>{{$info->IDname}}</td>
    </tr>
    <tr>
        <td>手机</td>
        <td>{{$info->phone}}</td>
    </tr>
    <tr>
        <td>注册时间</td>
        <td>{{$info->createtime}}</td>
    </tr>
    <tr>
        <td>第一次认证时间</td>
        <td>{{$info->certifi_time}}</td>
    </tr>
    <tr>
        <td>公司/店铺名称</td>
        <td>{{$info->company}}</td>
    </tr>
    <tr>
        <td>职位</td>
        <td>{{$info->post}}</td>
    </tr>
    <tr>
        <td>诚信值</td>
        <td>{{$honesty}}</td>
    </tr>
</table>