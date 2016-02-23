@extends($project_name.'-master')

@section('contenido')
    <script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
    <section class="container" id="ng-app" ng-app="app">
        <div ng-controller="ImagenMultiple" nv-file-drop="" uploader="uploader" filters="customFilter, sizeLimit">
        {{ Form::open(array('url' => 'admin/portfolio_completo/editar', 'files' => true, 'role' => 'form')) }}
            <h2><span>Editar obra</span></h2>
            <div class="marginBottom2">
                <a class="volveraSeccion" href="@if($seccion_next != 'null'){{URL::to('/'.Seccion::find($seccion_next) -> menuSeccion() -> url)}}@else{{URL::to('/')}}@endif"><i class="fa fa-caret-left"></i>Volver a @if($seccion_next != 'null'){{ Seccion::find($seccion_next) -> menuSeccion() -> nombre }}@else Home @endif</a>
            </div>
        
            @if(Auth::user()->can('cambiar_seccion_item'))
            <div class="row">
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
                <!-- Título del Producto, Obra o Muestra -->
                <div class="col-md-6 divDatos divCargaTitulo">
                    <h3>Título de la obra</h3>
                    <div class="form-group fondoDestacado">
                        <input class="form-control" type="text" name="titulo" placeholder="Título de la obra" required="true" value="{{ $item->titulo }}" maxlength="50">
                    </div>
                </div>

                <!-- Obra Destacada -->
                <div class="col-md-6 divDatos">
                    <h3>Destacado (opcional)</h3>
                    <div class="form-group fondoDestacado">
                        <p class="infoTxt"><i class="fa fa-info-circle"></i>...</p>
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
                                    <h4>Imágenes cargadas</h4>
                                </div>
                            @endif
                            @if(count($item->imagen_destacada()) > 0)
                            <div class="imgSeleccionadas">
                                <div class="col-md-2">
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
                                <div class="col-md-2">
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
                <div class="col-md-6 divDatos">
                    <!-- Texto Descriptivo del Producto u obra -->
                    <div class="divCargaTxtDesc">
                        <h3>Texto descriptivo de la obra</h3>
                        <div class="divEditorTxt fondoDestacado">
                            <textarea id="texto" contenteditable="true" name="cuerpo">{{ $item->portfolio()->portfolio_completo()->cuerpo }}</textarea>
                        </div>
                    </div>

                   <!-- Videos -->
                    <div class="divCargaVideos">
                        <h3>Videos</h3>
                        <div class="fondoDestacado">
                            <div class="row">
                                @foreach($item->videos as $video)
                                    <div class="col-md-4 marginBottom2">
                                        <iframe class="video-tc" src="@if($video->tipo == 'youtube')https://www.youtube.com/embed/@else//player.vimeo.com/video/@endif{{ $video->url }}"></iframe>
                                        <a onclick="borrarVideoReload('{{ URL::to('admin/video/borrar') }}', '{{$video->id}}');" class="btn pull-right"><i class="fa fa-times fa-lg"></i>eliminar</a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @if(count($item->videos) == 2)
                                        <div class="form-group marginBottom2">
                                            <input class="form-control" type="text" name="video[]" placeholder="URL de video">
                                        </div>
                                    @elseif(count($item->videos) == 1)
                                        <div class="form-group marginBottom2">
                                            <input class="form-control" type="text" name="video[]" placeholder="URL de video">
                                        </div>
                                        <div class="form-group marginBottom2">
                                            <input class="form-control" type="text" name="video[]" placeholder="URL de video">
                                        </div>
                                    @elseif(count($item->videos) == 0)
                                        <div class="form-group marginBottom2">
                                            <input class="form-control" type="text" name="video[]" placeholder="URL de video">
                                        </div>
                                        <div class="form-group marginBottom2">
                                            <input class="form-control" type="text" name="video[]" placeholder="URL de video">
                                        </div>
                                        <div class="form-group marginBottom2">
                                            <input class="form-control" type="text" name="video[]" placeholder="URL de video">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="clearfix"></div>  

        <div class="border-top">
            <input type="submit" value="Publicar" class="btn btn-primary">
            <a href="@if($seccion_next != 'null'){{URL::to('/'.Seccion::find($seccion_next) -> menuSeccion() -> url)}}@else{{URL::to('/')}}@endif" class="btn btn-default">Cancelar</a>
        </div>


            {{Form::hidden('continue', $continue)}}
            {{Form::hidden('id', $item->id)}}
            {{Form::hidden('portfolio_completo_id', $portfolio_completo->id)}}
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
    
    <script src="{{URL::to('ckeditor/ckeditor.js')}}"></script>
        <script src="{{URL::to('ckeditor/adapters/jquery.js')}}"></script>

@stop
