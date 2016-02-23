@extends('item.agregar-general')

@section('funcs')
<script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
@stop

@section('titulo_nombre') Nombre de la muestra @stop

@section('info_nombre') No puede haber dos muestras con igual nombre. Máximo {{$max_legth or '...'}} caracteres. @stop

@section('destacado-contenido')
<div class="radio divEstadoNuevo">
    <label>
        <input id="" class="" type="checkbox" name="item_destacado" value="A">
        Destacado
    </label>
</div>
<!-- <p class="infoTxt"><i class="fa fa-info-circle"></i>Los productos NUEVOS y las OFERTAS se muestran también en la home.</p> -->
@stop

@section('bloque-3')@stop

@section('archivos')@stop

@section('ubicacion')

    @include('ubicacion.modulo-ubicacion')

@stop

@section('hidden')

    {{Form::hidden('seccion_id', $seccion_id)}}
            
@stop