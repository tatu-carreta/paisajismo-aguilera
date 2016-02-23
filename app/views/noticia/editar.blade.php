@extends('item.editar-general')

@section('funcs')
<script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
@stop

@section('titulo_nombre') Título de la noticia @stop

@section('info_nombre') No puede haber dos noticias con igual nombre. Máximo {{$max_length or '...'}} caracteres. @stop

{{--
@section('destacado-contenido')
<div class="radio divEstadoNuevo">
    <label>
        <input id="" class="" type="checkbox" name="item_destacado" value="N" @if($item->destacado()) checked="true" @endif>
        Destacado
    </label>
</div>
@stop
--}}

@section('bloque-2')
    <!-- Fuente -->
    <div class="col-md-6 divDatos divCargaPrecio">
        <h3>Fuente de la noticia</h3>
        <div class="form-group fondoDestacado">
            <input id="" class="form-control inputWidth60 precio-number" type="text" name="fuente" placeholder="Fuente de la noticia" maxlength="100" value="{{ $noticia->fuente }}">
        </div>
    </div>
@stop

@section('bloque-3')
    <!-- Bajada -->
    <div class="col-md-6 divDatos divCargaPrecio">
        <h3>Bajada de la noticia</h3>
        <div class="form-group fondoDestacado">
            <input id="" class="form-control inputWidth60 precio-number" type="text" name="descripcion" placeholder="Bajada de la noticia" maxlength="100" value="{{ $item->descripcion }}">
        </div>
    </div>
@stop

@section('bloque-4')
    <!-- Fecha -->
    <div class="col-md-6 divDatos divCargaMarca">
        <h3>Fecha de la noticia</h3>
        <div class="fondoDestacado form-group">
            <div class="row">
                <div class="col-md-12">
                    <input class="form-control" type="text" name="fecha"  placeholder="Fecha de la noticia" required="true" maxlength="12" value="{{ date('d/m/Y', strtotime($noticia->fecha)) }}">
                </div>
            </div>
            <p class="infoTxt"><i class="fa fa-info-circle"></i>Debe respetar el formato dd/mm/aaaa</p>
        </div>
    </div>
@stop

@section('mas-datos')
    <div class="col-md-6 divDatos">
        <!-- Cuerpo de la noticia -->
        <div class="divCargaTxtDesc">
            <h3>Cuerpo de la noticia</h3>
            <div class="divEditorTxt fondoDestacado">
                <textarea id="texto" contenteditable="true" name="cuerpo">{{ $item->texto()->cuerpo }}</textarea>
            </div>
        </div>
    </div>
@stop

@section('cuerpo')@stop

@section('ubicacion')

    @include('ubicacion.modulo-ubicacion-editar')

@stop

@section('hidden')

    {{Form::hidden('continue', $continue)}}
    {{Form::hidden('id', $item->id)}}
    {{Form::hidden('noticia_id', $noticia->id)}}
    @if($seccion_next != 'null')
        {{Form::hidden('seccion_id', $seccion_next)}}
    @endif
            
@stop