@extends($project_name.'-master')

@section('contenido')
    <script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
    <section class="container" id="ng-app" ng-app="app">
        <div ng-controller="ImagenMultiple" nv-file-drop="" uploader="uploader" filters="customFilter, sizeLimit">
        {{ Form::open(array('url' => 'admin/portfolio_completo/agregar', 'files' => true, 'role' => 'form')) }}
            <h2><span>Nueva obra</span></h2>
            <div class="marginBottom2">
                <a class="volveraSeccion" href="{{URL::to('/'.Seccion::find($seccion_id) -> menuSeccion() -> url)}}"><i class="fa fa-caret-left"></i>Volver a {{ Seccion::find($seccion_id) -> menuSeccion() -> nombre }}</a>
            </div>
        
            <div class="row">
                <!-- Título del Producto, Obra o Muestra -->
                <div class="col-md-6 divDatos divCargaTitulo">
                    <h3>Título de la obra</h3>
                    <div class="form-group fondoDestacado">
                        <input class="form-control" type="text" name="titulo" placeholder="Título de la obra" required="true" maxlength="50">
                        <p class="infoTxt"><i class="fa fa-info-circle"></i>No puede haber dos productos con igual nombre. Máximo 9 caracteres.</p>
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
                            <div class="col-md-12" ng-show='imagenes_seleccionadas.length > 0'>
                                <h4>Imágenes cargadas</h4>
                            </div>

                            <div ng-repeat="img in imagenes_seleccionadas" class="imgSeleccionadas">
                                <div class="col-md-3">
                                    <div class="thumbnail">
                                        <input type="hidden" name="imagen_portada_ampliada[]" value="<% img.imagen_portada_ampliada %>">
                                        <img ng-src="<% img.src %>">
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
                            <textarea id="texto" contenteditable="true" name="cuerpo"></textarea>
                        </div>
                    </div>

                    <!-- Videos -->
                    <div class="divCargaVideos">
                        <h3>Videos</h3>
                        <div class="fondoDestacado">
                            <div class="form-group">
                                <input class="form-control" type="text" name="video[]" placeholder="URL de video">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="video[]" placeholder="URL de video">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="video[]" placeholder="URL de video">
                            </div> 
                            <p class="infoTxt"><i class="fa fa-info-circle"></i>Copie la URL del video de Youtube o Vimeo.</p>
                        </div>   
                    </div>
                </div>


                <div class="col-md-6 divDatos">
                    <!-- Indicar Sección a la que pertenece el producto -->
                    <div class="divModIndicarSeccion">
                        <h3>Otras ubicaciones (opcional)</h3>
                        <div class="modIndicarSeccion">
                                @foreach($menues as $men)
                                <div class="cadaSeccion">
                                    @if(count($men->children) == 0)
                                        <div>
                                            @foreach($men->secciones as $seccion)
                                                <span><input id="menu{{$men->id}}" type="checkbox" name="secciones[]" value="{{$seccion->id}}" @if($seccion->id == $seccion_id) checked="true" disabled @endif>{{-- @if($seccion->titulo != ""){{$seccion->titulo}}@else Sección {{$seccion->id}} @endif --}}</span>
                                            @endforeach
                                        </div>
                                        <div><label for="menu{{$men->id}}">{{$men->nombre}}</label></div>
                                    @endif
                                </div>
                                @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-top">
                <input type="submit" value="Publicar" class="btn btn-primary marginRight5">
                <a href="{{URL::to('/'.Seccion::find($seccion_id) -> menuSeccion() -> url)}}" class="btn btn-default">Cancelar</a>
            </div>


            {{Form::hidden('seccion_id', $seccion_id)}}
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
