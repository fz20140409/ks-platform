@extends('admin.layouts.default')
@section('content')
  <section class="content">
    <div class="row" style="background: #ffffff">
        <div class="box">
            <div style="margin-top: 20px">
      <div style="" class="col-sm-3 col-md-2 sidebar">
        <h1><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>日志文件</h1>
        <div class="list-group">
          @foreach($files as $file)
            <a style="color: #48494a" href="?l={{ base64_encode($file) }}"
               class="list-group-item @if ($current_file == $file) llv-active @endif">
              {{$file}}
            </a>
          @endforeach
        </div>
      </div>
      <div class="col-sm-9 col-md-10 table-container">
        @if ($logs === null)
          <div>
            Log file >50M, please download it.
          </div>
        @else
          <table id="table-log" class="table table-striped ">
            <thead>
            <tr>
              <th>错误级别</th>
              <th>环境</th>
              <th>日期</th>
              <th>错误内容</th>
            </tr>
            </thead>
            <tbody>

            @foreach($logs as $key => $log)
              <tr data-display="stack{{{$key}}}">
                <td class="text-{{{$log['level_class']}}}"><span class="glyphicon glyphicon-{{{$log['level_img']}}}-sign"
                                                                 aria-hidden="true"></span> &nbsp;{{$log['level']}}</td>
                <td class="text">{{$log['context']}}</td>
                <td class="date">{{{$log['date']}}}</td>
                <td class="text">
                  @if ($log['stack']) <a class="pull-right expand btn btn-default btn-xs "
                                         data-display="stack{{{$key}}}"><span
                            class="glyphicon glyphicon-search"></span></a>@endif
                  {{{$log['text']}}}
                  @if (isset($log['in_file'])) <br/>{{{$log['in_file']}}}@endif
                  @if ($log['stack'])
                    <div class="stack text-{{{$log['level_class']}}}" id="stack{{{$key}}}"
                         style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                    </div>@endif
                </td>
              </tr>
            @endforeach

            </tbody>
          </table>
        @endif
        <div>
          @if($current_file)
            <a href="?dl={{ base64_encode($current_file) }}"><span class="glyphicon glyphicon-download-alt"></span>
             下载文件</a>
            -
            <a id="delete-log" href="javascript:del('{{route('admin.logs.index')}}?del={{ base64_encode($current_file) }}')"><span
                      class="glyphicon glyphicon-trash"></span> 删除文件</a>
            @if(count($files) > 1)
              -
              <a id="delete-all-log" href="javascript:del('{{route('admin.logs.index')}}?delall=true')"><span class="glyphicon glyphicon-trash"></span> 删除所有文件</a>
            @endif
          @endif
        </div>
      </div>
            </div>
        </div>
    </div>
  </section>
  @endsection
@section('css')
  <link rel="stylesheet"
        href="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
  <style>


    h1 {
      font-size: 1.5em;
      margin-top: 0;
    }

    .stack {
      font-size: 0.85em;
    }

    .date {
      min-width: 75px;
    }

    .text {
      word-break: break-all;
    }

    a.llv-active {
      z-index: 2;
      background-color: #f9f9f9;
      border-color: #777;
    }
  </style>
  @endsection
@section('js')
    <script src="/plugins/layer/layer.js"></script>
  <script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
  <script>
      function del(url) {
          layer.confirm('确认删除？', {
              btn: ['确认','取消'] //按钮
          }, function(){
                $.ajax({
                    url:url,
                    success:function () {
                        location.reload()
                    }
                });
          }, function(){

          });

      }
      $(document).ready(function () {
          $('.table-container tr').on('click', function () {
              $('#' + $(this).data('display')).toggle();
          });
          $('#table-log').DataTable({
              "order": [1, 'desc'],
              "stateSave": true,
              "stateSaveCallback": function (settings, data) {
                  window.localStorage.setItem("datatable", JSON.stringify(data));
              },
              "stateLoadCallback": function (settings) {
                  var data = JSON.parse(window.localStorage.getItem("datatable"));
                  if (data) data.start = 0;
                  return data;
              },
              language: {
                  "sProcessing": "处理中...",
                  "sLengthMenu": "每页 _MENU_ 条",
                  "sZeroRecords": "没有匹配结果",
                  "sInfo": "第 _START_ 至 _END_ 条，共 _TOTAL_ 条",
                  "sInfoEmpty": "第 0 至 0 条，共 0 条",
                  "sInfoFiltered": "(由 _MAX_ 条结果过滤)",
                  "sInfoPostFix": "",
                  "sSearch": "全文搜索",
                  "sUrl": "",
                  "sEmptyTable": "表中数据为空",
                  "sLoadingRecords": "载入中...",
                  "sInfoThousands": ",",
                  "oPaginate": {
                      "sFirst": "首页",
                      "sPrevious": "上页",
                      "sNext": "下页",
                      "sLast": "末页"
                  },
                  "oAria": {
                      "sSortAscending": ": 以升序排列此列",
                      "sSortDescending": ": 以降序排列此列"
                  }
              }
          });

      });
  </script>
  @endsection