@extends('item.editar-general')

@section('funcs')
<script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
@stop

@section('titulo_nombre') Nombre de la muestra @stop

@section('info_nombre') No puede haber dos muestras con igual nombre. Máximo {{$max_legth or '...'}} caracteres. @stop

@section('destacado-contenido')
<div class="radio divEstadoNuevo">
    <label>
        <input id="" class="" type="checkbox" name="item_destacado" value="N" @if($item->destacado()) checked="true" @endif>
        Destacado
    </label>
</div>
@stop

@section('bloque-3')@stop

@section('archivos')@stop

@section('cuerpo')
<!-- Texto Descriptivo del Producto u obra -->
<div class="divCargaTxtDesc">
    <h3>Detalles técnicos</h3>
    <div class="divEditorTxt fondoDestacado">
        <textarea id="texto" contenteditable="true" name="cuerpo">{{ $item->muestra()->lang()->cuerpo }}</textarea>
    </div>
</div>
@stop

@section('ubicacion')

    @include('ubicacion.modulo-ubicacion-editar')

@stop

@section('hidden')

    {{Form::hidden('continue', $continue)}}
    {{Form::hidden('id', $item->id)}}
    {{Form::hidden('muestra_id', $muestra->id)}}
    @if($seccion_next != 'null')
        {{Form::hidden('seccion_id', $seccion_next)}}
    @endif
            
@stop