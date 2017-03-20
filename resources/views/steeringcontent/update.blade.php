
@extends('layout1')

@section('page-title')
    Thêm mới Ban - Đơn Vị
@stop
@section('page-toolbar')
@stop

@section('content')

    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    @foreach ($data as $row)
    {!! Form::open(array('route' => 'steeringcontent-update', 'class' => 'form')) !!}
    {{ Form::hidden('id', $row->id, array('id' => 'id')) }}

    <div class="form-group">
        {!! Form::label('Nội dung chỉ đạo') !!}
        {!! Form::textarea('content', $row->content,
            array('required',
                  'class'=>'form-control',
                  'placeholder'=>'Nội dung chỉ đạo')) !!}
    </div>
    <div class="form-group">
        {!! Form::label('Thuộc kết luận') !!}
        {!! Form::select('source', $source, $row->source,
                array('no-required','class'=>'form-control')
        ) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Đơn vị chủ trì') !!}
        {!! Form::select('firtunit', $firstunit,$row->unit,
                array('no-required','class'=>'form-control')
        ) !!}
    </div>
    <div class="form-group">
        {!! Form::label('Đơn vị Phối hợp') !!}
        <ul>
            @foreach ($secondunit as $id=>$r1)
                <li>{!! Form::checkbox('secondunit[]', $id, in_array($id,$dtfollow)) !!} {{ $r1 }}</li>
            @endforeach
        </ul>
    </div>
    <div class="form-group form-inline">
        {!! Form::label('Thời gian hoàn thành') !!}
        {!! Form::text('deadline', $row->deadline,
            array('required',
                  'class'=>'form-control datepicker',
                  'placeholder'=>'Thời gian hoàn thành')) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Đơn vị Xác nhận') !!}
        <ul>
            <li>{!! Form::radio('confirm', 'C',($row->xn=='C')) !!} Đơn vị chưa xác nhận</li>
            <li>{!! Form::radio('confirm', 'X',($row->xn=='X')) !!} Đơn vị đã xác nhận</li>
            <li>{!! Form::radio('confirm', 'K',($row->xn=='K')) !!} Đơn vị không nhận</li>
        </ul>

    </div>

    <div class="form-group">
        {!! Form::label('Theo dõi của văn phòng') !!}
        {!! Form::textarea('note', $row->note,
            array('no-required',
                  'class'=>'form-control',
                  'placeholder'=>'Theo dõi của văn phòng')) !!}
    </div>



    <div class="form-group">
        {!! Form::label('Đánh giá') !!}
        {!! Form::radio('status', 0,($row->status==0)) !!} Chưa hoàn thành
        {!! Form::radio('status', 1,($row->status==1)) !!} Hoàn thành
        {!! Form::radio('status', -1,($row->status==-1)) !!} Bị hủy

    </div>

    <div class="form-group">
        {!! Form::submit('Cập nhật',
          array('class'=>'btn btn-primary')) !!}
    </div>
    {!! Form::close() !!}
    @endforeach
    <script>
        $( document ).ready(function() {
            // Handler for .ready() called.
            $('.datepicker').datepicker({format: 'dd/mm/yyyy'});
        });
    </script>
@stop