@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
    <div class="modal">
        @section('inicio_form') {{ Form::open(array('url' => 'admin/item/agregar', 'files' => true)) }} @show
        <h2>carga y modificación de productos</h2>
        <div id="error" class="error"><span></span></div>
        <div id="correcto" class="correcto"><span></span></div>

        @include('imagen.modulo-imagen-euge')

        <p class="aclaracion"><i class="icon-info"></i>Dibujá sobre la foto la zona de recorte para definir la miniatura.<br>
            La foto ampliada mantendrá la proporción de tu foto original.</p>
        <p class="aclaracion"><i class="icon-info"></i>Las fotos originales deben pesar entre 100k y 2MB</p>

        <div><a class="btnDestacar"><i class="fa fa-thumb-tack fa-lg"></i><input type="checkbox" name="item_destacado" value="A">Destacar</a></div>

        <div class="floatRight">
            <a onclick="cancelarPopup('agregar-item', '{{$seccion_id}}');" class="btnGris marginRight5">Cancelar</a>
            <input type="submit" value="Guardar" class="btn">

        </div>
        <div class="clear"></div>
        {{Form::hidden('seccion_id', $seccion_id)}}
        {{Form::hidden('titulo', '')}}
        {{Form::hidden('descripcion', '')}}
        {{Form::close()}}
    </div>
@stop

@section('footer')@stop