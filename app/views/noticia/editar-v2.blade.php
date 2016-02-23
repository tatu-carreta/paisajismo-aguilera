@extends($project_name.'-master')

@section('contenido')
    <script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
    <section class="container" id="ng-app" ng-app="app">
        <div ng-controller="ImagenMultiple" nv-file-drop="" uploader="uploader" filters="customFilter, sizeLimit">
        {{ Form::open(array('url' => 'admin/noticia/editar', 'files' => true, 'role' => 'form')) }}
            <h2><span>Editar noticia</span></h2>
            <div class="marginBottom2">
                <a class="volveraSeccion" href="@if($seccion_next != 'null'){{URL::to('/'.Seccion::find($seccion_next) -> menuSeccion() -> url)}}@else{{URL::to('/')}}@endif"><i class="fa fa-caret-left"></i>Volver a @if($seccion_next != 'null'){{ Seccion::find($seccion_next) -> menuSeccion() -> nombre }}@else Home @endif</a>
            </div>
        
            @if(Auth::user()->can('cambiar_seccion_item'))
            <div class="row marginBottom2">
                <div class="col-md-6">
                    <select name="seccion_nueva_id" class="form-control">
                        <option value="">Seleccione Nueva Sección</option>
                        @foreach($secciones as $seccion)
                            <option value="{{$seccion->id}}" @if($seccion->id == $item->seccionItem()->id) selected @endif>@if($seccion->nombre != ""){{$seccion->nombre}}@else Sección {{$seccion->id}} - {{$seccion->menuSeccion()->nombre}}@endif</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            
            <div class="row">
                <div class="col-md-6 divDatos">
                    <h3>Título de la noticia</h3>
                    <div class="form-group fondoDestacado">
                        <input class="form-control" type="text" name="titulo" placeholder="Título de la noticia" required="true" value="{{ $item->titulo }}" maxlength="100">
                    </div>
                </div>
                
                <div class="col-md-6 divDatos">
                    <h3>Fecha</h3>
                    <div class="form-group fondoDestacado">
                        <input class="form-control" type="date" name="fecha" placeholder="Fecha de la noticia" required="true" value="{{ $noticia->fecha }}" maxlength="12">
                    </div>
                </div>

                <div class="col-md-6 divDatos">
                    <h3>Fuente</h3>
                    <div class="form-group fondoDestacado">
                        <input class="form-control" type="text" name="fuente" placeholder="Fuente de la noticia" required="true" value="{{ $noticia->fuente }}" maxlength="50">
                    </div>
                </div>

                <div class="col-md-6 divDatos">
                    <h3>Descripción</h3>
                    <div class="form-group fondoDestacado">
                        <input class="form-control" type="text" name="descripcion" placeholder="Descripción de la noticia" required="true" value="{{ $item->descripcion }}" maxlength="200">
                    </div>
                </div>
            </div>

            <div class="row marginBottom2">
            <!-- Abre columna de imágenes -->
                <div class="col-md-12 cargaImg">
                        <div class="fondoDestacado">
                            <h4>Recorte de imágenes</h4>
                            <input type="hidden" ng-model="url_public" ng-init="url_public = '{{URL::to('/')}}'">
                            @include('imagen.modulo-imagen-angular-crop-horizontal-multiples')
                        <div class="row">
                            @if((count($item->imagen_destacada()) > 0) || (count($item->imagenes) > 0))
                                <div class="col-md-12">
                                    <h3>Imágenes cargadas</h3>
                                </div>
                            @endif
                            @if(count($item->imagen_destacada()) > 0)
                            <div class="imgSeleccionadas">
                                <div class="col-md-3">
                                    <div class="thumbnail">
                                        <input type="hidden" name="imagen_crop_editar[]" value="{{$item->imagen_destacada()->id}}">
                                        <img class="marginBottom1" src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}" alt="{{$item->titulo}}">
                                        <input class="form-control" type="text" name="epigrafe_imagen_crop_editar[]" value="{{$item->imagen_destacada()->epigrafe}}">
                                        <i onclick="borrarImagenReload('{{ URL::to('admin/imagen/borrar') }}', '{{$item->imagen_destacada()->id}}');" class="fa fa-times-circle fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @foreach($item->imagenes as $img)
                             <div class="imgSeleccionadas">
                                <div class="col-md-3">
                                    <div class="thumbnail">
                                        <input type="hidden" name="imagen_crop_editar[]" value="{{$img->id}}">
                                        <img class="marginBottom1" src="{{ URL::to($img->carpeta.$img->nombre) }}" alt="{{$item->titulo}}">
                                        <input class="form-control" type="text" name="epigrafe_imagen_crop_editar[]" value="{{$img->epigrafe}}">
                                        <i onclick="borrarImagenReload('{{ URL::to('admin/imagen/borrar') }}', '{{$img->id}}');" class="fa fa-times-circle fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div ng-repeat="img in imagenes_seleccionadas" class="imgSeleccionadas">
                                <div class="col-md-3">
                                    <div class="thumbnail">
                                        <input type="hidden" name="imagen_portada_ampliada[]" value="<% img.imagen_portada_ampliada %>">
                                        <img class="marginBottom1" ng-src="<% img.src %>">
                                        <input type="hidden" name="epigrafe_imagen_portada[]" value="<% img.epigrafe %>">
                                        <input type="hidden" name="imagen_portada_crop[]" value="<% img.imagen_portada %>">
                                        <i ng-click="borrarImagenCompleto($index)" class="fa fa-times-circle fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col-md-12">
                    <h3>Cuerpo de la noticia</h3>
                    <div class="divEditorTxt marginBottom2">
                        <textarea id="texto" contenteditable="true" name="cuerpo">{{ $item->texto()->cuerpo }}</textarea>
                    </div>
                </div>
            </div>

            @if($seccion_next != 'null')
                <div class="fondoDestacado modIndicarSeccion">
                    <h3>Ubicación</h3>
                        @foreach($menues as $men)
                        <div class="cadaSeccion">
                            @if(count($men->children) == 0)
                                <div>
                                    @foreach($men->secciones as $seccion)
                                        <span><input id="menu{{$men->id}}" type="checkbox" name="secciones[]" value="{{$seccion->id}}" @if(in_array($seccion->id, $item->secciones->lists('id'))) checked="true" @endif @if($seccion->id == $seccion_next) disabled @endif>{{-- @if($seccion->titulo != ""){{$seccion->titulo}}@else Sección {{$seccion->id}} @endif --}}</span>
                                    @endforeach
                                </div>
                                <div><label for="menu{{$men->id}}">{{$men->nombre}}</label></div>
                            @endif
                        </div>
                        @endforeach
                </div>
            @else
                @foreach($item->secciones as $seccion)
                    <input type="hidden" name="secciones[]" value="{{$seccion->id}}">
                @endforeach
            @endif

            <div class="clear"></div>

            <div class="borderTop">
                <input type="submit" value="Publicar" class="btn btn-primary marginRight5">
                <a href="@if($seccion_next != 'null'){{URL::to('/'.Seccion::find($seccion_next) -> menuSeccion() -> url)}}@else{{URL::to('/')}}@endif" class="btn btn-default">Cancelar</a>
            </div>

            {{Form::hidden('continue', $continue)}}
            {{Form::hidden('id', $item->id)}}
            {{Form::hidden('noticia_id', $noticia->id)}}
            @if($seccion_next != 'null')
                {{Form::hidden('seccion_id', $seccion_next)}}
            @endif
        {{Form::close()}}
        </div>
    </section>
@stop

@section('footer')

    @parent

    <script src="{{URL::to('js/angular-1.3.0.min.js')}}"></script>
    <script src="{{URL::to('js/angular-file-upload.js')}}"></script>
    <script src="{{URL::to('js/ng-img-crop.js')}}"></script>
    <script src="{{URL::to('js/controllers.js')}}"></script>

@stop
