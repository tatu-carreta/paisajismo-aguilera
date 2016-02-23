@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
    <div class="modal">
        @section('inicio_form') {{ Form::open(array('url' => 'admin/item/editar', 'files' => true)) }} @show
            <h2>carga y modificación de productos</h2>

            <div class="imagenCarga">
                @if(!is_null($item->imagen_destacada()))
                    <div class="marginBottom">
                        <img alt="{{$item->titulo}}"  src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}">
                    </div>
                    <input class="block anchoTotal marginBottom" type="text" name="epigrafe" placeholder="epígrafe" value="{{ $item->imagen_destacada()->epigrafe }}">
                    {{Form::hidden('imagen_id', $item->imagen_destacada()->id)}}
                    <a onclick="borrarImagen('../admin/imagen/borrar', '{{$item->imagen_destacada()->id}}', '{{$item->id}}', '../admin/item/editar/');">Borrar Imagen</a>
                @else
                    @include('imagen.modulo-imagen-euge')
                    <p class="aclaracion"><i class="icon-info"></i>Dibujá sobre la foto la zona de recorte para definir la miniatura.<br>
                        La foto ampliada mantendrá la proporción de tu foto original.</p>
                    <p class="aclaracion"><i class="icon-info"></i>Las fotos originales deben pesar entre 100k y 2MB</p>
                @endif
            </div>

            @if(!$item->destacado())
                <div><a class="btnDestacar"><i class="fa fa-thumb-tack fa-lg"></i><input type="checkbox" name="item_destacado" value="A">Destacar</a></div>
            @else
                <div><a class="btnDestacar"><i class="fa fa-thumb-tack fa-lg"></i><input type="checkbox" name="item_destacado" value="B">Quitar Destacado</a></div>
            @endif
            <div class="floatRight">
                <a onclick="cancelarPopup('agregar-item', '{{ $item->seccionItem()->id }}');" class="btnGris marginRight5">Cancelar</a>
                <input type="submit" value="Guardar" class="btn">

            </div>
            <div class="clear"></div>
            {{Form::hidden('id', $item->id)}}
            {{Form::hidden('titulo', '')}}
            {{Form::hidden('descripcion', '')}}
        {{Form::close()}}
    </div>
@stop

@section('footer')@stop