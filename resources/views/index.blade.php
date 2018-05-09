@extends('layouts.master')
@section('title')
{{trans('view.index.headtitle')}}
@endsection
@section('content')
@if($errors->any())

<center><div id="errors" class="alert alert-danger" style="text-align: center; font-size: 16px; width: 83%;height: auto;">{{$errors->first()}}</div></center>
@endif
<center><img src="src/images/mantenimiento.gif" style="height:450px;width:auto;"></center>
<div style="margin-top:50px;"></div>
<div class="row index-circle-features" style="margin-left:162px;">
    <div class="col-md-4 index-circle-feature" style="margin-left:-3px;" >
        <i class="fa fa-users" style=" padding: 28px -1 8px 38px"></i>
        <h3 style="margin-left:-22px">¿QUIENES SOMOS?</h3>
        <span class="animated fadeInDown hidden-xs hidden-sm" style="margin-left:-22px">¿Quienes Somos? </span>
        <span class="animated fadeInDown hidden-xs hidden-sm" style="margin-left:-46px">Conocenos un poco mas</span>

    </div>
    <div class="col-md-4 index-circle-feature">
        <a href="{{ URL::to('contact') }}"><i class="fa fa-envelope " style=" padding: 25px 1px 4px 25px"></i></a>
        <h3>CONTACTANOS</h3>
        <span class="animated fadeInDown hidden-xs hidden-sm" style="margin-left:-52px;">Cualquier duda que tengas<br>¡No dudes en contactarnos! </span>
        <span class="visible-xs visible-sm">Cualquier duda que tengas<br>¡No dudes en contactarnos! </span>
    </div>
    <div class="col-md-4 index-circle-feature hidden-sm hidden-xs">
        <a href="{{ URL::to('register') }}"><i class="fa fa-male " style="padding:25px 38px;"></i></a>
        <h3 style="margin-left:8px !important">REGISTRATE</h3>
        <span class="animated fadeInDown hidden-xs hidden-sm" style="margin-left:-14px;">¡Es fácil y rápido! <br>&nbsp;</span>
        <span class="visible-xs visible-sm">¡Es fácil y rapido!<br>&nbsp;</span>
    </div>
</div>
@endsection

