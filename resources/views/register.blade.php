@extends('layouts.master')
@section('title')
{{trans('view.register.headtitle')}}
@endsection
@section('content')

@if($errors->any())

<center>
    <div class="alert alert-danger" style="text-align: center; font-size: 16px; width: 83%;height: auto;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</center>
@endif

<div style="margin-left: 8%;margin-right: 8%;">
    <section id="content">
        <div class="main padder">
            <div class="row">
                <div class="">
                    <section class="">
                        <header class="panel-heading text-center">
                            <h2><i class="fa fa-plus-square"></i> {{ trans('view.register.title') }}</h2>
                        </header>
                        {!! FORM::open(array('url' => 'registro', 'class' => 'panel-body','name'=>'registerClientForm' )) !!}

                            <div class="block">
                                <center><label>{{ trans('view.register.name.label') }}</label></center>
                                {!! FORM::input('first_name','first_name',Input::get('first_name'),array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('view.register.name.placeholder'), 'required' => 'required')) !!}

                            </div>
                            <div class="block">
                                <center><label>{{ trans('view.register.last_name.label') }}</label></center>
                                {!! FORM::input('last_name','last_name',Input::get('last_name'),array('id' => 'formulario', 'class' => 'form-control', 'placeholder' => trans('view.register.last_name.placeholder'), 'required' => 'required')) !!}

                            </div>
                            <div class="block">
                                <center><label>{{ trans('view.register.email.label') }}</label></center>
                                {!! FORM::input('email','email',Input::get('email'),array('id' => 'email', 'class' => 'form-control', 'placeholder' => trans('view.register.email.placeholder'), 'required' => 'required')) !!}

                            </div>
                            <div class="block">
                                <center><label>{{ trans('view.register.phone.label') }}</label></center>
                                {!! FORM::input('phone','phone',Input::get('phone'),array('id' => 'phone', 'class' => 'form-control', 'placeholder' => trans('view.register.phone.placeholder'), 'required' => 'required')) !!}

                            </div>

                            <div class="block">
                                <center><label>{{ trans('view.register.password.label') }}</label></center>
                                <input name="password" pattern=".{8,22}" required type="password" id="password" placeholder="{{ trans('view.register.password.placeholder') }}" class="form-control"  title="{{ trans('view.register.password.title') }}" oninvalid="setCustomValidity('{{ trans('view.register.password.title') }}')"
                                       onchange="try{setCustomValidity('')}catch(e){}">
                            </div>
                            <div class="checkbox">
                                <left><label>
                                        <input type="checkbox" id="checkBox" required="" title="{{ trans('view.register.checkbox')}}">
                                        {{ trans('view.register.form.term-condition.label') }} <a href="#" class="text-table" onclick="overlay()">{{ trans('view.register.form.term-condition.href') }}</a>
                                    </label></left>
                            </div>
                            <br>

                            <center><input type="submit" id="registerButton" class="btn btn-primary" value="{{ trans('view.register.form.submit') }}">
                                <br>
                                <br>
                                <p><small>{{ trans('view.register.form.login.label') }}</small><a href="{{ URL::to('ingresar') }}" class="text-muted text-center naranja">{{trans('view.register.form.login.href') }}</a></p>
                            </center>
                        {!! FORM::close() !!}
                        <br>
                        <br>
                    </section>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="overlay">
    <div id = "scroll">
        <div id="cerrar">
            <a class="pull-right cr-pointer fa fa-lg fa-times" onclick="overlay()" style="margin: 5px"></a><br>
        </div><br>
            <p>
            <?php
                //traer de la base de datos dependiendo el idioma
                $terms = Config::get('client.terms');
                $terms = preg_replace('/<br> /',"<br>",$terms);
                echo '<br> '.$terms;
            ?>
        </p>
    </div>
</div>
@endsection
