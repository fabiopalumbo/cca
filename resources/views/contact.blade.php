@extends('layouts.master')

@section('content')

    <header class="panel-heading text-center">
        <h2><i class="fa fa-plus-square"></i>{{trans('view.contact.title')}}</h2>
    </header>
    {!! FORM::open(array('url' => 'contact',
                        'method' => 'post')) !!}

        <ul class="errors">
        @foreach($errors->all('<li>:message</li>') as $message)

        @endforeach

        </ul>

        <div class="form-group">
            <label>{{ trans('view.contact.email.label') }}</label>
            {!! FORM::input('email','email',Input::get('email'),array('id' => 'email', 'class' => 'form-control', 'placeholder' => trans('view.contact.email.placeholder'), 'required' => 'required')) !!}
        </div>

        <div class="form-group">
            <label>{{ trans('view.contact.name.label') }}</label>
            {!! FORM::input('name','name',Input::get('nombre'),array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('view.contact.name.placeholder'), 'required' => 'required')) !!}
        </div>

        <div class="form-group">
            <label>{{ trans('view.contact.last_name.label') }}</label>
            {!! FORM::input('apellido','apellido',Input::get('apellido'),array('id' => 'formulario', 'class' => 'form-control', 'placeholder' => trans('view.contact.last_name.placeholder'), 'required' => 'required')) !!}
        </div>

        <div class="form-group">
            <label>{{ trans('view.contact.message.label') }}</label>
            {!! FORM::textarea('message' ,'',  array('placeholder' => trans('view.contact.message.placeholder') , 'class' => 'form-control', 'rows' => 4 )) !!}
        </div>
        </br>

    <center>
        <div class="submit">
        {!! FORM::submit(trans('view.contact.submit'),['class' => 'btn btn-primary']) !!}
        </div>
    </center>
    {!! FORM::close() !!}

@endsection
