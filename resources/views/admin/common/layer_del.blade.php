<script>
    function del(url) {
        layer.confirm('确认删除？', {
            btn: ['确认', '取消']
        }, function () {
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function (data) {
                    if (data.msg == -1) {
                        layer.alert(data.info);
                        return false;
                    }
                    if (data.msg == 1) {
                        layer.alert('删除成功');
                        location.reload();
                    }
                    else {
                        layer.alert('删除失败');
                    }
                }
            });
        });
    }
</script>
