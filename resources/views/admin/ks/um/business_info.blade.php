<table class=" table table-hover table-bordered">

    <tr>
        <td>主营渠道</td>
        <td>
            @foreach($salechanel as $value)
                <div>
                    <div style="float: left;">
                        {{$value['name']}} {{empty($value['child']) ? '' : '>>>'}}
                    </div>
                    <div style="display: inline-block;">
                        @foreach($value['child'] as $item)
                            {{$item['name']}}  @if(!empty($item['child']))({{implode(',', array_column($item['child'], 'name'))}})@endif
                            <br />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </td>
    </tr>
    <tr>
        <td>品类</td>
        <td>
            @foreach($category as $value)
                <div>
                    <div style="float: left;">
                        {{$value['name']}} {{empty($value['child']) ? '' : '>>>'}}
                    </div>
                    <div style="display: inline-block;">
                        @foreach($value['child'] as $item)
                            {{$item['name']}}  @if(!empty($item['child']))({{implode(',', array_column($item['child'], 'name'))}})@endif
                            <br />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </td>
    </tr>
    <tr>
        <td>业务辐射区</td>
        <td>
            @foreach($merchant_dealers_ywfs as $value)
                <div>
                    <div style="float: left;">
                        {{$value['name']}} {{empty($value['child']) ? '' : '>>>'}}
                    </div>
                    <div style="display: inline-block;">
                    @foreach($value['child'] as $item)
                        {{$item['name']}}  @if(!empty($item['child']))({{implode(',', array_column($item['child'], 'name'))}})@endif
                        <br />
                    @endforeach
                    </div>
                </div>
            @endforeach
        </td>
    </tr>
    <tr>
        <td>主要经销品牌</td>
        <td>
            @foreach($brand as $value)
                {{$value->zybrand}} &nbsp;&nbsp;&nbsp;&nbsp; {{$value->proportion}} {{isset($value->zybrand) ? '%' : ''}} <br />
            @endforeach
        </td>
    </tr>
    <tr>
        <td>主营产品</td>
        <td>{{$merchant->maingoods}}</td>
    </tr>
</table>