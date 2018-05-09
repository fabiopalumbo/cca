@extends('layouts.master')
@section('title')
{{trans('view.forgot.headtitle')}}
@endsection
@section('content')

@if($errors->any())

<center><div id="errors" class="alert alert-danger" style="text-align: center; font-size: 16px; width: 83%;height: auto;">{{$errors->first()}}</div></center>
@endif
<div style="margin-left: 8%;margin-right: 8%;">
    <section id="content">
        <div class="main padder">
            <div class="row">
                <div class="">
                    <section class="">
                        <header class="panel-heading text-center">
                            <h2><i class="fa fa-plus-square"></i> {{ trans('view.forgot.title') }}</h2>
                        </header>
                        {!! FORM::open(array('url' => 'api/user/password/recovery', 'class' => 'panel-body','name'=>'recoveryClientForm','id'=>'recoveryClientForm','method'=>'POST' )) !!}

                        <div class="block">
                            <center><label>{{ trans('view.forgot.email.label') }}</label></center>
                            <input name="email" id="email" type="email" placeholder="{{ trans('view.forgot.email.placeholder') }}" class="form-control"  title="{{ trans('view.forgot.email.title') }}" required>
                        </div>
                        <center><input type="submit" id="forgotButton" class="btn btn-primary" value="{{ trans('view.forgot.submitvalue') }}">
                            {!! FORM::close() !!}
                    </section>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
