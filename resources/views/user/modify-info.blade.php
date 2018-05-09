@extends('layouts.master')
@section('title')
{{trans('view.modify.headtitle')}}
@endsection

@section('content')

@if($errors->any())
<center>
    <div id="errors" class="alert alert-danger" style="text-align: center; font-size: 16px; width: 83%;height: auto;margin-top: 0px;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>

@else
    <div id="errors" class="alert alert-danger" style="text-align: center; font-size: 16px; width: 83%;height: auto;visibility: hidden; margin-top: 0px;">
</center>
@endif
</div>
<div style="margin-left: 8%;margin-right: 8%;margin-top: -30px;">
    <section id="content">
        <div class="main padder">
            <div class="row">
                <div class="">
                    <section class="">
                        <header class="panel-heading text-center">
                            <h2><i class="fa fa-plus-square"></i> {{ trans('view.modify.title') }}</h2>
                        </header>
                        <div id="datos" style="margin: auto;">
                        {!! FORM::open(array('url' => 'api/user/update', 'class' => 'panel-body','name'=>'modifyClientForm', 'files' => true )) !!}
                            <div class="block">
                                <center><label><h4>{{ trans('view.modify.name.label').': '. Auth::user()->name }}</h4></label></center>
                                {!! FORM::input('name','name',Input::get('name'),array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('view.modify.name.placeholder'), 'title' => trans('view.modify.name.title'))) !!}
                            </div>
                            <div class="block">
                                <center><label><h4>{{ trans('view.modify.last_name.label').': '.Auth::user()->surname }}</h4></label></center>
                                {!! FORM::input('surname','surname',Input::get('surname'),array('id' => 'surname', 'class' => 'form-control', 'placeholder' => trans('view.modify.last_name.placeholder'), 'title' => trans('view.modify.last_name.title'))) !!}
                            </div>
                            <div class="block">
                                <center><label><h4>{{ trans('view.modify.phone.label').': '.Auth::user()->phone }}</h4></label></center>
                                {!! FORM::input('phone','phone',Input::get('phone'),array('id' => 'phone', 'class' => 'form-control', 'placeholder' => trans('view.modify.phone.placeholder'), 'title' => trans('view.modify.phone.title'))) !!}
                            </div>
                            <div class="block">
                                <center><div class="fileUpload btn btn-default" style="margin-top: 10px; margin-bottom: 10px;">
                                        <center><label>{{ trans('view.modify.image.label')}}</label></center>
                                    <input type="file" accept="image/jpg, image/gif, image/png" name="image" class="upload" title="{{trans('view.modify.image.title')}}"></center></div>
                                </center>
                            <center><input type="submit" id="modifyButton" class="btn btn-primary" value="{{ trans('view.modify.form.submit') }}">
                        {!! FORM::close() !!}
                        </div>

                        <div id="pass" style="margin: auto;">
                        {!! FORM::open(array('url' => 'api/user/updatePass', 'class' => 'panel-body','name'=>'passwordClientForm','id' =>'passwordClientForm' )) !!}
                        
                            <div class="block">
                                <center><label><h4>{{ trans('view.modify.oldpassword.label') }}</h4></label></center>
                                <input name="oldPassword" pattern=".{8,22}"  type="password" id="oldPassword" placeholder="{{ trans('view.modify.oldpassword.placeholder') }}" class="form-control"  title="{{ trans('view.modify.oldpassword.title') }}" oninvalid="setCustomValidity('{{ trans('view.modify.oldpassword.title') }}')"
                                       onchange="try{setCustomValidity('')}catch(e){}">
                            </div>
                            <div class="block">
                                <center><label><h4>{{ trans('view.modify.newpassword.label') }}</h4></label></center>
                                <input name="newPassword" pattern=".{8,22}"  type="password" id="newPassword" placeholder="{{ trans('view.modify.newpassword.placeholder') }}" class="form-control"  title="{{ trans('view.modify.newpassword.title') }}" oninvalid="setCustomValidity('{{ trans('view.modify.newpassword.title') }}')"
                                   onchange="try{setCustomValidity('')}catch(e){}">
                            </div>
                            <div class="block">
                                <center><label><h4>{{ trans('view.modify.repeatpassword.label') }}</h4></label></center>
                                <input name="repeatPassword" pattern=".{8,22}"  type="password" id="repeatPassword" placeholder="{{ trans('view.modify.repeatpassword.placeholder') }}" class="form-control"  title="{{ trans('view.modify.repeatpassword.title') }}" oninvalid="setCustomValidity('{{ trans('view.modify.repeatpassword.title') }}')"
                                   onchange="try{setCustomValidity('')}catch(e){}">
                            </div>
                            <center><input type="submit" id="modifyButton" class="btn btn-primary" value="{{ trans('view.modify.form.submit') }}">
                        {!! FORM::close() !!}

                        </div>

                        <br>
                        <br>
                        <center><a href="#"  id="show_hide" class="text-muted text-center naranja"></a></center>
                    </section>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#datos").show();
        $("#pass").hide();
        var btn = $('#show_hide');
        btn.show();
        btn.text('<?php echo trans('view.modify.swaptopass');?>');
        var band = false;
        btn.click(function(){
            if (band == false){
                btn.text('<?php echo trans('view.modify.swaptodata');?>');
                band = true;
            }
            else{
                btn.text('<?php echo trans('view.modify.swaptopass');?>');
                band = false;
            }
            $("#datos").slideToggle();
            $("#pass").slideToggle();
            btn.blur();
        });

    });
    var band = false;
    $("#passwordClientForm").submit(function(e){
        e.preventDefault();
        if ($('#newPassword').val() == $('#repeatPassword').val()){
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
