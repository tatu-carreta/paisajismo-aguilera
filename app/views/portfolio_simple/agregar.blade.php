@extends('item.agregar-general')

@section('bloque-1')@stop

@section('bloque-3')@stop

@section('archivos')@stop

@section('bloque-4')@stop

@section('detalles-tecnicos')@stop

@section('modulo_imagen')
    @include('imagen.modulo-imagen-angular-crop-horizontal')
@stop

@section('hidden')

    {{Form::hidden('seccion_id', $seccion_id)}}
    {{Form::hidden('titulo', '')}}
    {{Form::hidden('descripcion', '')}}
            
@stop