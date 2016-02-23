@extends($project_name.'-master')

@section('contenido')
    <script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
    
    <section class="container">
        {{ Form::open(array('url' => 'admin/evento/agregar', 'files' => true)) }}
            <h2 class="marginBottom2"><span>Carga y modificación de eventos</span></h2>
            <div id="error" class="error" style="display:none"><span></span></div>
            <div id="correcto" class="correcto ok" style="display:none"><span></span></div>

            @if(isset($seccion))
                @if(Auth::user()->can('cambiar_seccion_item'))
                    <select name="seccion_nueva_id">
                        <option value="">Seleccione Nueva Sección</option>
                        @foreach($secciones as $seccion)
                        <option value="{{$seccion->id}}" @if($seccion->id == $item->seccionItem()->id) selected @endif>@if($seccion->nombre != ""){{$seccion->nombre}}@else Sección {{$seccion->id}} - {{$seccion->menuSeccion()->nombre}}@endif</option>
                        @endforeach
                    </select>
                @endif
            @endif
            
            <!-- Abre columna de descripción -->
            <div class="col70Admin datosProducto">
                <h3>Título del evento</h3>
                <input class="block anchoTotal marginBottom" type="text" name="titulo" placeholder="Título" required="true" maxlength="50" @if(isset($item)) value="{{ $item->titulo }}" @endif>

                <h3>Fecha Desde</h3>
                <input class="block anchoTotal marginBottom" type="date" name="fecha_desde" placeholder="Fecha" required="true" maxlength="50" @if(isset($item)) value="{{ $evento->fecha_desde }}" @endif>

                <h3>Fecha Hasta</h3>
                <input class="block anchoTotal marginBottom" type="date" name="fecha_hasta" placeholder="Fuente" required="true" maxlength="50" @if(isset($item)) value="{{ $evento->fecha_desde }}" @endif>

                <h3>Descripción</h3>
                <input class="block anchoTotal marginBottom" type="text" name="descripcion" placeholder="Descripción" required="true" maxlength="50" @if(isset($item)) value="{{ $item->descripcion }}" @endif>

                <h3>Cuerpo</h3>
                <div class="divEditorTxt marginBottom2">
                    <textarea id="texto" contenteditable="true" name="cuerpo">@if(isset($item)){{ $item->texto()->cuerpo }}@endif</textarea>
                </div>
            </div>

            <!-- Abre columna de imágenes -->
            <div class="col30Admin fondoDestacado padding1 cargaImg">
                <h3>Imagen principal</h3>
                @if(isset($item))
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
                @else
                    @include('imagen.modulo-imagen-evento-maxi')
                @endif
            </div>

            <div class="clear"></div>
            <!-- cierran columnas -->
            
            

            <div class="punteado">
                <input type="submit" value="Publicar" class="btn marginRight5">
                <a onclick="window.history.back();" class="btnGris">Cancelar</a>
            </div>

            @if(isset($item))
                {{Form::hidden('continue', $continue)}}
                {{Form::hidden('id', $item->id)}}
                {{Form::hidden('evento_id', $evento->id)}}
            @else
                {{Form::hidden('seccion_id', $seccion_id)}}
            @endif
        {{Form::close()}}
    </section>
@stop
