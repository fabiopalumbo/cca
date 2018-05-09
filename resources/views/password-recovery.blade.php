@extends('layouts.master')
@section('title')
{{trans('view.login.headtitle')}}
@endsection
@section('content')
<center>
@if($errors->any())
    <div id="errors"  class="alert alert-danger nombre" style="text-align: center; font-size: 16px; width: 83%;height: auto; visibility: visible;">
    {{$errors->first()}}
@else
    <div id="errors"  class="alert alert-danger nombre" style="text-align: center; font-size: 16px; width: 83%;height: auto; visibility: hidden;">
@endif
</div></center>

<div style="margin-left: 8%;margin-right: 8%;">
    <section id="content">
        <div class="main padder">
            <div class="row">
                <div class="">
                    <section class="">
                        <header class="panel-heading text-center">
                            <h2><i class="fa fa-plus-square"></i> {{ trans('view.recovery.title') }}</h2>
                        </header>
                        {!! FORM::open(array('url' => 'api/user/updateRecovery', 'class' => 'panel-body','name'=>'changePassClientForm','id'=>'changePassClientForm' )) !!}

                            <div class="block">
                                <center><label>{{ trans('view.recovery.password.label') }}</label></center>
                                <input name="password" id="inputpassword" type="password" pattern=".{8,22}" placeholder="{{ trans('view.recovery.password.placeholder') }}" class="form-control"  title="{{ trans('view.recovery.password.title') }}" required
                                       oninvalid="setCustomValidity('<?php echo trans('view.recovery.password.title');?>')"
                                       onchange="try{setCustomValidity('')}catch(e){}">
                            </div>
                            <div class="block">
                                <center><label>{{ trans('view.recovery.repeatpassword.label') }}</label></center>
                                <input name="repeatpassword" id="inputrepeat" type="password" pattern=".{8,22}" placeholder="{{ trans('view.recovery.repeatpassword.placeholder') }}" class="form-control"  title="{{ trans('view.recovery.repeatpassword.title') }}" required
                                       oninvalid="setCustomValidity('<?php echo trans('view.recovery.repeatpassword.title');?>')"
                                       onchange="try{setCustomValidity('')}catch(e){}">
                            </div>
                                <input type="hidden" value="{{$token}}" name="token" id = "token" />

                            <center><input type="submit"  id="recoveryButton" class="btn btn-primary" value="{{ trans('view.recovery.submitvalue') }}">
                            {!! FORM::close() !!}
                    </section>
                </div>
            </div>
        </div>
    </section>
</div>
<!--<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>-->
<script type="text/javascript">
    var band = false;
    $("#changePassClientForm").submit(function(e){
        var self = this;
        e.preventDefault();
        if ($('#inputpassword').val() == $('#inputrepeat').val()){
            this.submit();
            return true;
        }
        else{
            var error = $('#errors');
            if (!band){
                error.append( '<p><?php echo trans('view.recovery.passwordmismatch');?></p>');
                error.css("visibility","visible");
                band = true;
            }
            return false;
        }
    });
</script>
@endsection
