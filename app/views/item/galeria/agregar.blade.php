@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
    <div>
        <h2>Crear Galería</h2>

        {{ Form::open(array('url' => 'admin/galeria/agregar', 'files' => true)) }}

            <input class="block anchoTotal marginBottom" type="text" name="titulo" placeholder="Título">
            <div class="divEditorTxt">
                <textarea class="block anchoTotal marginBottom" name="descripcion" placeholder="Descripción"></textarea>
            </div>
            <div class="floatRight">
                <a onclick="cancelarPopup('agregar-seccion');" class="btnGris marginRight5">Cancelar</a>
                <input type="submit" value="Guardar" class="btn">

            </div>
            <div class="clear"></div>

            @include('imagen.modulo-galeria-angular')

            {{Form::hidden('seccion_id', $seccion_id)}}
        {{Form::close()}}
    </div>
@stop

@section('footer')@stop