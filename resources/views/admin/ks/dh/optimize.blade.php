<table class="table table-hover">
    <tr>

        <th>优化原因</th>
        <th>选择次数</th>
    </tr>
        @foreach($data as $item)
            <tr>
                <td>{{$item->r_name}}</td>
                <td>{{$item->num}}</td>
            </tr>
        @endforeach

</table>