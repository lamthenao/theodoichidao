@extends('layout1')

@section('page-title')
    Update User
@stop
@section('page-toolbar')
@stop

@section('content')

    <h1>Thêm người sử dụng</h1>

        @if ( $errors->count() > 0 )
            <p>The following errors have occurred:</p>

            <ul>
                @foreach( $errors->all() as $message )
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endif


        {!! Form::open(array('route' => 'user-update', 'class' => 'form')) !!}
        <div class="form-group">
            {!! Form::label('Tên đăng nhập') !!}
            {!! Form::text('username', "",
                array('required',
                      'class'=>'form-control',
                      'placeholder'=>'Tên đăng nhập')) !!}
        </div>

    <div class="form-group">
        {!! Form::label('Password') !!}
        {!! Form::password('password',
            array('required',
                  'class'=>'form-control',
                  'placeholder'=>' Mật khẩu ít nhất 6 ký tự.')) !!}
    </div>



        <div class="form-group">
            {!! Form::label('Tên đầy đủ') !!}
            {!! Form::text('fullname', "",
                array('required',
                      'class'=>'form-control',
                      'placeholder'=>'Nhập họ tên người sử dụng')) !!}
        </div>


        <div class="form-group">
            {!! Form::label('Quyền hạn') !!}
            {!! Form::select('group', $group,
                    array('no-required','class'=>'form-control')
            ) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Đơn vị') !!}
            {!! Form::select('unit', $unit,
                    array('no-required','class'=>'form-control')
            ) !!}
        </div>

        <div class="form-group">
            {!! Form::submit('Cập nhật',
              array('class'=>'btn btn-primary')) !!}
        </div>
        {!! Form::close() !!}


@stop