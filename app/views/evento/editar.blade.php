@extends($project_name.'-master')

@section('contenido')
    <script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
    <section class="container">
        @if (Session::has('mensaje'))
            <div class="divAlerta error alert-success">{{ Session::get('mensaje') }}<i onclick="" class="cerrarDivAlerta fa fa-times fa-lg"></i></div>
        @endif
        {{ Form::open(array('url' => 'admin/evento/editar', 'files' => true)) }}
            <h2 class="marginBottom2"><span>Carga y modificación de eventos</span></h2>

        @if(Auth::user()->can('cambiar_seccion_item'))
            <select name="seccion_nueva_id">
                <option value="">Seleccione Nueva Sección</option>
                @foreach($secciones as $seccion)
                <option value="{{$seccion->id}}" @if($seccion->id == $item->seccionItem()->id) selected @endif>@if($seccion->nombre != ""){{$seccion->nombre}}@else Sección {{$seccion->id}} - {{$seccion->menuSeccion()->nombre}}@endif</option>
                @endforeach
            </select>
        @endif
            <!-- abre datos del Producto-->
            <div class="col70Admin datosProducto">
                <h3>Título del evento</h3>
                <input class="block anchoTotal marginBottom" type="text" name="titulo" placeholder="Título" required="true" value="{{ $item->titulo }}" maxlength="50">
                
                <h3>Fecha Desde</h3>
                <input class="block anchoTotal marginBottom" type="date" name="fecha_desde" placeholder="Fecha Desde" required="true" value="{{ $evento->fecha_desde }}" maxlength="50">
                
                <h3>Fecha Hasta</h3>
                <input class="block anchoTotal marginBottom" type="date" name="fecha_hasta" placeholder="Fecha Hasta" required="true" value="{{ $evento->fecha_hasta }}" maxlength="50">

                <h3>Descripción</h3>
                <input class="block anchoTotal marginBottom" type="text" name="descripcion" placeholder="Descripción" required="true" value="{{ $item->descripcion }}" maxlength="50">
                
                <h3>Cuerpo</h3>
                <div class="divEditorTxt marginBottom2">
                    <textarea id="texto" contenteditable="true" class="" name="cuerpo">{{ $item->texto()->cuerpo }}</textarea>
                </div>

            </div>
            <!-- Cierra columna ancha -->


            <!-- Columna 60% imágenes-->
            <div class="col30 fondoDestacado padding1 cargaImg">
                <h3>Imagen principal</h3>
                @if(!is_null($item->imagen_destacada()))
                    <div class="divCargaImgProducto">
                        <div class="marginBottom1 divCargaImg">
                            <img alt="{{$item->titulo}}"  src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}">
                            <i onclick="borrarImagenReload('{{ URL::to('admin/imagen/borrar') }}', '{{$item->imagen_destacada()->id}}');" class="fa fa-times fa-lg"></i>
                        </div>
                        <input type="hidden" name="imagen_portada_editar" value="{{$item->imagen_destacada()->id}}">
                        <input class="block anchoTotal marginBottom" type="text" name="epigrafe_imagen_portada_editar" placeholder="Ingrese una descripción de la foto" value="{{ $item->imagen_destacada()->epigrafe }}">
                    </div>
                @else
                    @include('imagen.modulo-imagen-evento-maxi')
                @endif
                <div class="clear"></div>
            </div>
            <!-- fin Columna imágenes-->

            <div class="clear"></div>

            <div class="punteado">
                <input type="submit" value="Guardar" class="btn marginRight5">
                <a onclick="window.history.back();" class="btnGris">Cancelar</a>
            </div>

            {{Form::hidden('continue', $continue)}}
            {{Form::hidden('id', $item->id)}}
            {{Form::hidden('evento_id', $evento->id)}}
        {{Form::close()}}
    </section>
@stop
