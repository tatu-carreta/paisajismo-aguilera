@extends('item.agregar-general')

@section('funcs')
    <script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
@stop

@section('titulo_nombre') Título de la noticia @stop

@section('info_nombre') No puede haber dos noticias con igual nombre. Máximo {{$max_length or '...'}} caracteres. @stop

{{--
@section('destacado-contenido')
<div class="radio divEstadoNuevo">
    <label>
        <input id="" class="" type="checkbox" name="item_destacado" value="A">
        Destacado
    </label>
    <p class="infoTxt"><i class="fa fa-info-circle"></i>Las noticias destacadas se muestran también en la home.</p>
</div>
@stop
--}}

@section('bloque-2')
    <!-- Fuente -->
    <div class="col-md-6 divDatos">
        <h3>Fuente de la noticia</h3>
        <div class="form-group fondoDestacado">
            <input id="" class="form-control" type="text" name="fuente" placeholder="Fuente de la noticia" maxlength="100">
        </div>
    </div>
    
@stop

@section('bloque-3')
    
    <!-- Bajada -->
    <div class="col-md-6 divDatos">
        <h3>Bajada de la noticia</h3>
        <div class="form-group fondoDestacado">
            <input id="" class="form-control" type="text" name="descripcion" placeholder="Bajada de la noticia" maxlength="100">
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
                    <input class="form-control" type="text" name="fecha"  placeholder="Fecha de la noticia" required="true" maxlength="12">
                </div>
            </div>
            <p class="infoTxt"><i class="fa fa-info-circle"></i>Debe respetar el formato dd/mm/aaaa</p>
        </div>
    </div>
@stop

@section('mas-datos')
    <div class="col-md-12 divDatos">
        <!-- Cuerpo de la noticia -->
        <div class="divCargaTxtDesc">
            <h3>Cuerpo de la noticia</h3>
            <div class="divEditorTxt fondoDestacado">
                <textarea id="texto" contenteditable="true" name="cuerpo"></textarea>
            </div>
        </div>
    </div>
@stop

@section('cuerpo')@stop

@section('ubicacion')

    @include('ubicacion.modulo-ubicacion')
    
@stop

@section('hidden')

    {{Form::hidden('seccion_id', $seccion_id)}}
            
@stop