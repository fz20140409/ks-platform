@extends('admin.layouts.default')
@section('t1','合作机会')
@section('t2','详情')
    @section('css')
       <style>
           .box-body #xq tr td:first-child{
               color: #00a7d0;
           }
       </style>
        @endsection
@section('js')
    <script src="/plugins/bootstrap/tab.js"></script>
@endsection
@section('content')
    <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <!-- form start -->
                <div class="box-header">
                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active">
                            <a href="#home" data-toggle="tab">
                                内容详情
                            </a>
                        </li>
                        <li><a href="#ios" data-toggle="tab">评论与回复</a></li>
                    </ul>

                </div>

                <div class=" box-body table-responsive no-padding">
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade in active" id="home">
                            <table id="xq" class=" table table-hover table-bordered">
                                <tr>
                                    <td>发布者</td>
                                    <td>{{$info->company}}</td>
                                </tr>
                                <tr>
                                    <td>发布时间</td>
                                    <td>{{$info->createtime}}</td>
                                </tr>
                                <tr>
                                    <td>所属分类</td>
                                    <td>{{$info->catename}}</td>
                                </tr>
                                <tr>
                                    <td>标题</td>
                                    <td>{{$info->title}}</td>
                                </tr>
                                <tr>
                                    <td>内容</td>
                                    <td>{{$info->intro}}</td>
                                </tr>
                                <tr>
                                    <td>图片</td>
                                    <td><img width="20%" src="{{$info->icon}}"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="ios">
                            <table class=" table table-hover table-bordered">
                                <tr>
                                    <td>用户</td>
                                    <td>内容</td>
                                    <td>图片</td>
                                    <td>时间</td>
                                </tr>
                                @foreach($comments as $comment)
                                    <tr>
                                        <td width="20%">
                                            <img class="img-circle" width="10%" src="{{$comment->uicon}}">
                                            <p style="margin-top: 10px">{{$comment->username}}</p>
                                        </td>
                                        <td>{{$comment->content}}</td>
                                        <td>{{$comment->uicon}}</td>
                                        <td>{{$comment->create_time}}</td>
                                    </tr>
                                    @endforeach


                            </table>

                        </div>
                    </div>

                </div>
                <div class="box-footer  ">
                    <a href="{{route('admin.ks.coop.index')}}" class="btn btn-default">返回</a>

                </div>


            </div>
        </div>
        </div>
    </section>
    @endsection