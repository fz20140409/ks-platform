<script>
    @if(session()->has('tip'))
        layer.msg('{{session('tip')}}');
    @endif
</script>