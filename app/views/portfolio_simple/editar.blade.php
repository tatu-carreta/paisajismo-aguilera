@extends('item.editar-general')

@section('bloque-1')@stop

@section('bloque-3')@stop

@section('archivos')@stop

@section('bloque-4')@stop

@section('detalles-tecnicos')@stop

@section('modulo_imagen')
    <h3>Imagen principal</h3>
    @if(!is_null($item->imagen_destacada()))
        <div>
            <div class="marginBottom1 divImgCargadaPortSimple">
                <img alt="{{$item->titulo}}"  src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}">
                <i onclick="borrarImagenReload('{{ URL::to('admin/imagen/borrar') }}', '{{$item->imagen_destacada()->id}}');" class="fa fa-times-circle fa-lg"></i>
            </div>
            <input type="hidden" name="imagen_portada_editar" value="{{$item->imagen_destacada()->id}}">
            <input class="block marginBottom form-control" type="text" name="epigrafe_imagen_portada_editar" placeholder="Ingrese una descripción de la foto" value="{{ $item->imagen_destacada()->epigrafe }}">
        </div>
    @else
        @include('imagen.modulo-imagen-angular-crop-horizontal')
    @endif
@stop

@section('hidden')

    {{Form::hidden('titulo', '')}}
    {{Form::hidden('continue', $continue)}}
    {{Form::hidden('id', $item->id)}}
    {{Form::hidden('portfolio_id', $portfolio->id)}}
    @if($seccion_next != 'null')
        {{Form::hidden('seccion_id', $seccion_next)}}
    @endif
            
@stop