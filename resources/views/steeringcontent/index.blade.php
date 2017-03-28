@extends('layout1')

@section('page-title')
    Danh mục nhiệm vụ
@stop

@section('content')

    <div class="text-center title">Danh mục nhiệm vụ</div>

    @if ($steering != false)
        <div class="text-center">
            <div>Danh sách các nhiệm vụ theo nguồn chỉ dạo</div>
            <div style="color: red">{{$steering->code}} - {{$steering->name}}</div>
        </div>
    @elseif(\App\Roles::accessAction(Request::path(), 'add'))
        {{ Html::linkAction('SteeringcontentController@edit', 'Thêm nhiệm vụ', array('id'=>0), array('class' => 'btn btn-my')) }}
    @endif
        <script language="javascript">
            function removebyid(id) {

                if (confirm("Bạn có muốn xóa?")) {
                    document.getElementById("id").value = id;
                    frmdelete.submit();
                }


            }
        </script>

        {!! Form::open(array('route' => 'steeringcontent-delete', 'class' => 'form', 'id' => 'frmdelete')) !!}
        {{ Form::hidden('id', 0, array('id' => 'id')) }}
        {!! Form::close() !!}
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="note-cl cl2"></div><a id="a2" class="a-status" href="javascript:filterStatus(2)"><span class="note-tx">Đã hoàn thành</span>(Đúng hạn, <span class="count-st" id="row-st-2"></span>)</a><br>
            <div class="note-cl cl3"></div><a id="a3" class="a-status" href="javascript:filterStatus(3)"><span class="note-tx">Đã hoàn thành</span>(Quá hạn, <span class="count-st" id="row-st-3"></span>)</a>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="note-cl cl1"></div><a id="a1" class="a-status" href="javascript:filterStatus(1)"><span class="note-tx">Chưa hoàn thành</span>(Trong hạn, <span class="count-st" id="row-st-1"></span>)</a><br>
            <div class="note-cl cl4"></div><a id="a4" class="a-status" href="javascript:filterStatus(4)"><span class="note-tx">Chưa hoàn thành</span>(Quá hạn, <span class="count-st" id="row-st-4"></span>)</a>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="note-cl cl5"></div><a id="a5" class="a-status" href="javascript:filterStatus(5)"><span class="note-tx">Nhiệm vụ sắp hết hạn</span> (<span class="count-st" id="row-st-5"></span>)</a><br>
            <div class="note-cl cl6"></div><a id="a6" class="a-status" href="javascript:filterStatus(6)"><span class="note-tx">Nhiệm vụ đã bị hủy</span> (<span class="count-st" id="row-st-6"></span>)</a>
        </div>
    </div>
    <table id="table" class="table table-bordered table-hover row-border hover order-column">
        <thead>
        <tr>
            <th></th>
            <th> Tên nhiệm vụ<br><input type="text" style="width: 100%; min-width: 120px"></th>
            <th> Nguồn chỉ đạo<br><input type="text" style="max-width: 100px"></th>
            <th> Đơn vị đầu mối<input type="text" style="width: 100%; min-width: 120px;"></th>
            <th> Đơn vị phối hợp<br><input type="text" style="width: 100%; min-width: 120px;"></th>
            <th> Thời hạn HT<br><input type="text" class="datepicker" style="max-width: 80px"></th>
            <th> Tiến độ<br><input type="text" style="max-width: 100px"></th>
            @if(\App\Roles::checkPermission())
                <th class="td-action"></th>
                <th class="td-action"></th>
            @endif
            <th><input type="text" id="filter-status" class="hidden"></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($lst as $idx=>$row)
            @if(\App\Roles::accessRow(Request::path(), $row->created_by))
            <?php
            $st = 1;
            if($row->status == 1){
                if ($row->complete_time < $row->deadline){
                    $st = 2;
                }else{
                    $st = 3;
                }
            }else if ($row->status == -1){
                $st = 6;
            }else{
                if (date('Y-m-d') > $row->deadline){
                    $st = 4;
                }else if (date('Y-m-d',strtotime("+7 day")) > $row->deadline){
                    $st = 5;
                }else{
                    $st = 1;
                }
            }
            ?>

            <tr class="row-st-{{$st}}" id="row-{{$row->id}}" deadline="{{$row->deadline}}">
                <td>{{$idx + 1}}</td>
                <td> {{$row->content}} </td>
                @if ( !in_array($row->source, $allsteeringcode) )
                    <td> {{ $row->source }} </td>
                @else
                    <td><a href="steeringcontent?source={{$row->source}}"> {{ $row->source }} </a> </td>
                @endif

                <td onclick="javascript:showunit({{$idx}})">
                    <ul class="unit-list" id="unit-list{{$idx}}">
                        @php ($n = 0)
                        @foreach($units = explode(',', $row->unit) as $i)
                            <?php
                                $spl = explode('|', $i);
                                $validate = false;
                                $val = "";
                                if ($spl[0] == 'u' && isset($unit[$spl[1]])){
                                    $validate = true;
                                    $val = $unit[$spl[1]];
                                    $n++;
                                } else if ($spl[0] == 'h' && isset($user[$spl[1]])){
                                    $validate = true;
                                    $val = $user[$spl[1]];
                                    $n++;
                                }
                            ?>
                            @if ($validate)
                                @if ($loop->iteration < 3)
                                    <li> • {{$val}}</li>
                                @else
                                    <li class="more"> • {{$val}}</li>
                                @endif
                            @endif
                        @endforeach
                        @if ($n > 2)
                                <li class="more-link" hide="1"><a name="more-link-{{$idx}}">[+] Xem thêm</a></li>
                        @endif
                    </ul>
                </td>
                <td onclick="javascript:showfollow({{$idx}})">
                    <ul class="unit-list" id="follow-list{{$idx}}">
                    @php ($n = 0)
                    @foreach($units = explode(',', $row->follow) as $i)
                            <?php
                            $spl = explode('|', $i);
                            $validate = false;
                            $val = "";
                            if ($spl[0] == 'u' && isset($unit[$spl[1]])){
                                $validate = true;
                                $val = $unit[$spl[1]];
                                $n++;
                            }else if ($spl[0] == 'h' && isset($user[$spl[1]])){
                                $validate = true;
                                $val = $user[$spl[1]];
                                $n++;
                            }
                            ?>
                            @if ($validate)
                                @if ($loop->iteration < 3)
                                    <li> • {{$val}}</li>
                                @else
                                    <li class="more"> • {{$val}}</li>
                                @endif
                            @endif
                    @endforeach
                    @if ($n > 2)
                        <li class="more-link" hide="1"><a name="more-link-{{$idx}}">[+] Xem thêm</a></li>
                    @endif
                    </ul>
                </td>
                <td> {{ ($row->deadline != '')?Carbon\Carbon::parse($row->deadline)->format('d/m/Y'):'' }}</td>
                @if(\App\Roles::accessAction(Request::path(), 'status'))
                    <td id="progress-{{$row->id}}" data-id="{{$row->id}}" class="progress-update"> {{$row->progress}}</td>
                @else
                    <td id="progress-{{$row->id}}">{{$row->progress}}</td>
                @endif

                {{--<td>--}}
                {{--@if($row->status === 1)--}}
                {{--<span class="label label-sm label-success"> Hoàn thành </span>--}}
                {{--@elseif($row->status === 0)--}}
                {{--<span class="label label-sm label-warning"> Chưa hoàn thành </span>--}}
                {{--@elseif($row->status === -1)--}}
                {{--<span class="label label-sm label-danger"> Hủy </span>--}}
                {{--@else--}}
                {{--<span class="label label-sm label-info"> Mới </span>--}}
                {{--@endif--}}
                {{--</td>--}}
                @if(\App\Roles::accessAction(Request::path(), 'edit'))
                    <td>
                        <a href="{{$_ENV['ALIAS']}}/steeringcontent/update?id={{$row->id}}"><img height="20" border="0"
                                                                               src="{{$_ENV['ALIAS']}}/img/edit.png"></a>
                    </td>
                @endif
                @if(\App\Roles::accessAction(Request::path(), 'delete'))
                    <td>
                        <a href="javascript:removebyid('{{$row->id}}')"><img height="20" border="0"
                                                                             src="{{$_ENV['ALIAS']}}/img/delete.png"></a>
                    </td>
                @endif
                <td class="hidden">{{$st}}</td>
            </tr>
            @endif
        @endforeach
        </tbody>
    </table>
    <div class="panel-button"></div>
    <div id="modal-progress" class="modal fade" role="dialog">
        <div class="modal-dialog"  style="min-width: 80%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Theo dõi tiến độ</h4>
                </div>
                <div class="modal-body" style="padding-top: 0px !important;">
                    <form id="form-progress">
                        <input id="steering_id" type="hidden" name="steering_id">
                        <div class="form-group from-inline">
                            <label>Ghi chú tiến độ</label>
                            <textarea name="note" required id="pr-note" rows="2" class="form-control"></textarea>
                        </div>

                        <div class="form-group  from-inline">
                            <label>Tình trạng</label>
                            <input type="radio" name="pr_status" value="0" checked>  Nhiệm vụ chưa hoàn thành&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="pr_status" value="1">  Nhiệm vụ đã hoàn thành&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="pr_status" value="-1"> Nhiệm vụ bị hủy
                        </div>
                        <div class="form-group form-inline">
                            <label>Ngày cập nhật</label>
                            <input name="time_log" type="text" class="datepicker form-control" id="progress_time"
                                   required value="{{date('d/m/Y')}}">
                            <input class="btn btn-my pull-right" type="submit" value="Lưu">
                        </div>
                    </form>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Nội dung</th>
                            <th>Người cập nhật</th>
                            <th>Thời gian</th>
                        </tr>
                        </thead>
                        <tbody id="table-progress"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        var current_date = "{{date('d/m/Y')}}";
//        var showpr = false;
//        $("#form-progress").hide();
//        function showAddProgress() {
//            if (showpr) {
//                showpr = false;
//                $("#form-progress").hide();
//            } else {
//                showpr = true;
//                $("#form-progress").show();
//            }
//        }
        function showDetailProgress(id) {
            $(".loader").show();
            $("#steering_id").val(id);
            $.ajax({
                url: "{{$_ENV['ALIAS']}}/api/progress?s=" + id,
                success: function (result) {
                    $(".loader").hide();
                    var html_table = "";
                    for (var i = 0; i < result.length; i++) {
                        var r = result[i];
                        html_table += "<tr>";
                        html_table += "<td>" + r.note + "</td>"
                        html_table += "<td>" + r.created + "</td>"
                        html_table += "<td>" + r.time_log + "</td>"
                        html_table += "</tr>"
                    }
                    $("#table-progress").html(html_table);
                    $("#modal-progress").modal("show");
                },
                error: function () {
                    alert("Xảy ra lỗi nội bộ");
                    $(".loader").hide();
                }
            });
        }

        function resetFromProgress(){
            $("#pr-note").val("");
            $("#progress_time").val(current_date);
            $("input[name=pr_status][value='0']").prop('checked', true);
//            $("#form-progress").hide();
        }

        function reCount(){
            $(".count-st").each(function() {
                console.log($(this).attr('id'));
                $(this).html($('.' + $(this).attr('id')).length);
            });
        }

        function showunit(unit) {
            if($("#unit-list" + unit + " .more-link").attr("hide") == 1) {
                $("#unit-list" + unit + " .more").show();
                $("#unit-list" + unit + " .more-link a").text("[-] Thu gọn");
                $("#unit-list" + unit + " .more-link").attr("hide",0);
            } else {
                $("#unit-list" + unit + " .more").hide();
                $("#unit-list" + unit + " .more-link a").text("[+] Xem thêm");
                $("#unit-list" + unit + " .more-link").attr("hide",1);
            }

        }
        function showfollow(unit) {

            if($("#follow-list" + unit + " .more-link").attr("hide") == 1) {
                $("#follow-list" + unit + " .more").show();
                $("#follow-list" + unit + " .more-link a").text("[-] Thu gọn");
                $("#follow-list" + unit + " .more-link").attr("hide",0);
            } else {
                $("#follow-list" + unit + " .more").hide();
                $("#follow-list" + unit + " .more-link a").text("[+] Xem thêm");
                $("#follow-list" + unit + " .more-link").attr("hide",1);
            }

        }

        function reStyleRow(id, status, time_log){
            var time_split = time_log.split("/");
            var time = time_split[2] + "-" + time_split[1] + "-" + time_split[0];
            if (status == "-1"){
                $("#row-" + id).attr('class', 'row-st-6');
            }else if (status == "1"){
                if (time <= $("#row-" + id).attr('deadline')){
                    $("#row-" + id).attr('class', 'row-st-2');
                }else{
                    $("#row-" + id).attr('class', 'row-st-3');
                }
            }
        }

        $(document).ready(function () {

            @if(\App\Roles::accessAction(Request::path(), 'status'))
            $( ".progress-update" ).on( "click", function() {
                showDetailProgress($( this ).attr("data-id"))
                console.log( "#ID: " + $( this ).attr("data-id") );
            });
            @endif



            $('.datepicker').datepicker({format: 'dd/mm/yyyy'});
            reCount();
            $("#form-progress").submit(function (e) {
                e.preventDefault();
                var note = $("#pr-note").val();
                var steering_id = $("#steering_id").val();
                var status = $('input[name="pr_status"]:checked').val()
                var time_log = $("#progress_time").val();
                $(".loader").show();
                var url = "{{$_ENV['ALIAS']}}/api/updateprogress";
                console.log(url);
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {note: note, steering_id: steering_id, status: status, time_log: time_log},
                    success: function (result) {
                        console.log(result);
                        $(".loader").hide();
                        $("#modal-progress").modal("hide");
                        $("#progress-" + steering_id).html(note)
                        resetFromProgress();
                        reStyleRow(steering_id, status, time_log);
                    },
                    error: function () {
                        alert("Xảy ra lỗi nội bộ");
                        $(".loader").hide();
                    }
                });
            });
            // DataTable
            var table = $('#table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ],
                            format: {
                                body: function (data, row, column, node) {
                                    return data.replace(/<(?:.|\n)*?>/gm, '').replace(/(\r\n|\n|\r)/gm,"").replace(/ +(?= )/g,'').replace(/&amp;/g,' & ').replace(/&nbsp;/g,' ').replace(/•/g,"\r\n•").replace(/[+] Xem thêm/g,"").trim();
                                }
                            },
                            modifier: {
                                page: 'current'
                            },
                        },
                        title: 'Danh mục nhiệm vụ (Ngày ' + current_date + ")",
                        orientation: 'landscape',
                        customize: function (doc) {
                            doc.defaultStyle.fontSize = 10;
                        },
                        text: 'Xuất ra PDF',
                    },
                    {
                        extend: 'excel',
                        text: 'Xuất ra Excel',
                        title: 'Danh mục nhiệm vụ (Ngày ' + current_date + ")",
                        stripHtml: true,
                        decodeEntities: true,
                        columns: ':visible',
                        modifier: {
                            selected: true
                        },
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ],
                            format: {
                                body: function (data, row, column, node) {
                                    return data.replace(/<(?:.|\n)*?>/gm, '').replace(/(\r\n|\n|\r)/gm,"").replace(/ +(?= )/g,'').replace(/&amp;/g,' & ').replace(/&nbsp;/g,' ').replace(/•/g,"\r\n•").replace(/[+] Xem thêm/g,"").trim();
                                }
                            }
                        }
                    }
                ],
                bSort: false,
                bLengthChange: false,
                "pageLength": 20,
                "language": {
                    "url": "{{$_ENV['ALIAS']}}/js/datatables/Vietnamese.json"
                },
                "initComplete": function () {
                    $("#table_wrapper > .dt-buttons").appendTo("div.panel-button");
                }
            });

            // Apply the search
            table.columns().every(function () {
                var that = this;
                $('input', this.header()).on('keyup change changeDate', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                        reCount();
                    }
                });
                $('select', this.header()).on('change', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value ? '^' + this.value + '$' : '', true, false).draw();
                        reCount();
                    }
                });
            });
        });

        //loc theo trang thai
        function filterStatus(status){
            $(".a-status").css('font-weight', 'normal');
            $("#a" + status).css('font-weight', 'bold');
            $("#filter-status").val(status);
            $("#filter-status").trigger("change");
        }
    </script>
    <style>
        #table_filter {
            display: none;
        }
    </style>
@stop