<script src="/plugins/layer/layer.js"></script>
<script>
    @foreach(['success','waring','info','error'] as $msg)
    @if(session()->has($msg))
    layer.alert('{{session($msg)}}', {
        skin: 'layui-layer-molv'
        ,closeBtn: 0
    });
      
    @endif
    @endforeach
</script>