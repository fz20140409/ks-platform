@extends('admin.layouts.default')
@section('t1','对平台说')
@section('t2','功能')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
					<iframe src="" width="" height="" name="MyIFrame" id="MyIFrame"></iframe>

                    <!--box-footer-->
                </div>
            </div>
        </div>
    </section>
@endsection


@section('js')
<script src="/talkMe/js/jquery.cookie.js" type="text/javascript" charset="utf-8"></script>
<script src="/talkMe/webdemo/im/js/util.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	
	 $(function(){
	 	 $.ajaxSetup({
            layerIndex:-1,
            beforeSend: function () {
                
            },
            complete: function () {
               
            },
            error: function () {
                layer.alert('部分数据加载失败，可能会导致页面显示异常，请刷新后重试', {
                    skin: 'layui-layer-molv'
                    , closeBtn: 0
                    , shift: 4 //动画类型
                });
            }
        });
	 	function getIframeContent(){  //获取iframe中文档内容
			 var doc;
			 if (document.all){ // IE 
			  doc = document.frames["MyIFrame"].document; 
			 }else{ // 标准
			  doc = document.getElementById("MyIFrame").contentDocument; 
			 }
			 return doc;
		} 
		var win=getIframeContent()
		if(!readCookie("laravel_session")){
			win.location.href ="{{route('admin.login')}}"; 
		}else{
			$.get("/talk/getLoginInfo",function(res){
				console.log(res)
				setCookie('uid', res.account)
				setCookie('sdktoken', res.token)
				win.location.href ="/talkMe/webdemo/im/main.html"; 

			})
		}

  	})
</script>
@endsection

@section('css')
	<style>
		#MyIFrame{
			width: 100%;
			height: 900px;
		}
	</style>
@endsection