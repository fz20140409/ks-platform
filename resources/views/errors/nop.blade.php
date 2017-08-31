<!DOCTYPE html>
<html>
<head>
    <title>{{$data['info']}}</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }
        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }
        .content {
            text-align: center;
            display: inline-block;
        }
        .title {
            font-size: 40px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">{{$data['info']}}, 浏览器页面将在<b id="loginTime">{{ $data['jump_time'] }}</b>秒后跳转......</div>
    </div>
</div>

<script src="/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
    $(function(){
        //循环倒计时，并跳转
        var url = "{{ $data['url'] }}";
        var loginTime = parseInt($('#loginTime').text());
        console.log(loginTime);
        var time = setInterval(function(){
            loginTime = loginTime-1;
            $('#loginTime').text(loginTime);
            if(loginTime==0){
                clearInterval(time);
                window.location.href=url;
            }
        },1000);
    })
    //点击跳转
    $('.btn-success').click(function () {
        var url = "{{ $data['url'] }}";
        window.location.href=url;
    })
</script>
</body>
</html>