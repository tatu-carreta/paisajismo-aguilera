@extends($project_name.'-master')

@section('title') Info Item @stop

@section('contenido')
<div class="row marketing">
    <h3>Info Item</h3>

    <div class="list-group">
        <span><b>Título:</b> {{ $item -> titulo }}</span><br>
        <span><b>Descripción:</b> {{ $item -> descripcion }}</span><br>
        <span><b>Url:</b> {{ $item -> url }}</span><br>
        <span><b>Estado:</b> {{ $item -> estado }}</span><br>
        <span><b>Fecha Carga:</b> {{ $item -> fecha_carga }}</span><br>
        <span><b>Usuario Carga:</b> {{ $item -> usuario_id_carga }}</span><br>
        @foreach($item -> categorias as $c)
        <span><b>Categoria:</b> {{ $c -> nombre }}</span>
        @endforeach

        {{-- <img src="{{$item->imagen_destacada()->carpeta}}{{$item->imagen_destacada()->nombre}}"> --}}
        @if(!is_null($item->imagen_destacada()))
        {{HTML::image($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre, $item->titulo)}}
        @endif

        @if(count($item->imagenes) > 0)
        GALERIA

        @foreach($item->imagenes as $img)
        {{HTML::image($img->miniatura()->carpeta.$img->miniatura()->nombre, $item->titulo)}}
        @endforeach
        @endif
    </div>

</div>

@stop