@extends('layouts.master')
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <!--nuestro título podrá ser modificado-->
    <title>CCA FONSOFT - Mercadolibre Auntenticacion</title>

</head>
<body style="margin: 0 0 0 0">
<div style="width:100%;height:54px;background-color:#fff059;border-bottom: 1px solid #d9d9d9" >
<img src="https://ui.mlstatic.com/navigation/1.1.10/mercadolibre/logo__large.png" style="height: 40px;top:7px;left:40px !important;position:absolute;width:159px" />
</div>
<div class="pull-left;">
    <h2 style="font-size:18px;font-family:Arial,Helvetica,'Nimbus Sans L',sans-serif;font-weight:normal">{{$msg}}</h2>
    @if(isset($permalink))
    <a style="text-decoration: none" href="{{$permalink}}"><input id="publishButton" type="button" value="Ir a la publicacion" class="medium blue"></a>
    @endif
</div>


</body>
</html>
