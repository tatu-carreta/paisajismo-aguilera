@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
    <div>
        <h2>Editar Galería</h2>

        {{ Form::open(array('url' => 'admin/galeria/editar', 'files' => true)) }}
            <input class="block anchoTotal marginBottom" type="text" name="titulo" placeholder="Título" value="{{$item->titulo}}">
            <div class="divEditorTxt">
                <textarea class="block anchoTotal marginBottom" name="descripcion" placeholder="Descripción">{{$item->descripcion}}</textarea>
            </div>
            <!-- Galeria de Imagenes -->
            @if(count($item->imagenes) > 0)
                <div class="galeria">
                    @foreach($item->imagenes as $img)
                        <a onclick="borrarImagen('../admin/imagen/borrar', '{{$img->id}}', '{{$item->id}}', '../admin/galeria/editar/');">Borrar Imagen</a>
                        <img src="{{ URL::to($img->carpeta.$img->nombre) }}">
                        <input type="hidden" name="imagenes_existentes[]" value="{{$img->id}}">
                        <input type="text" name="epigrafes_existentes[]" value="{{ $img->ampliada()->epigrafe }}">
                    @endforeach
                </div>
            @endif

            <div class="floatRight">
                <a onclick="cancelarPopup('agregar-seccion');" class="btnGris marginRight5">Cancelar</a>
                <input type="submit" value="Guardar" class="btn">
            </div>

            @include('imagen.modulo-galeria-maxi')
            {{Form::hidden('id', $galeria->id)}}

        {{Form::close()}}
    </div>
@stop

@section('footer')@stop