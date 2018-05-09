@extends('layouts.master')

@section('title')
{{trans('view.login.headtitle')}}
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
    {!! FORM::open(['url' => 'ingresar',
                                'class' => 'login-form',
                                'id' =>'login-modal',
                                'name' =>'loginClientForm',
                                 'method' => 'post']) !!}
    <div class="container" id="login-form">
    	<div class="text-center">
    	    <a class="login-logo"><img style="width:100px;height:auto;margin-bottom:40px" src="admin/images/logo-cca.png"></a>
    	</div>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 style="height: 45px;color:#003466">Iniciar Sesión</h2>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    {!! FORM::input('text','email',Input::get('email'),array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Usuario', 'required' => 'required')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group" style="margin-top:25px">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Clave" required/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="checkbox">
                                    <label><input name="verpass" type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'" >Mostrar clave</label>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <div class="clearfix">

                                {!! FORM::submit('Entrar',['class' => 'btn btn-primary pull-right']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! FORM::close() !!}
@endsection