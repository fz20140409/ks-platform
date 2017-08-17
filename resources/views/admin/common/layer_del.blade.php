<script>
    function del(url) {
        layer.confirm('确认删除？', {
            btn: ['确认', '取消']
        }, function () {
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function ($data) {
                    if ($data.msg == 1) {
                        layer.alert('删除成功');
                        location.reload();
                    }else if($data.msg == -1){
                        layer.alert('已经使用，无法删除');
                    }
                    else {
                        layer.alert('删除失败');
                    }
                }
            });
        });
    }
</script>
