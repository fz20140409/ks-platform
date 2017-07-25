<script src="/plugins/layer/layer.js"></script>
<script>
    @foreach(['success','waring','info','error'] as $msg)
    @if(session()->has($msg))
      layer.alert('{{session($msg)}}',{closeBtn: 0});
    @endif
    @endforeach
</script>