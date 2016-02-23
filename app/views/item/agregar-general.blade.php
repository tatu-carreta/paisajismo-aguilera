@extends($project_name.'-master')

@section('contenido')
    @yield('funcs')
    <section class="container"  id="ng-app" ng-app="app">    
        <div ng-controller="ImagenMultiple" nv-file-drop="" uploader="uploader" filters="customFilter, sizeLimit">
        {{ Form::open(array('url' => 'admin/'.$modulo_pagina_nombre.'/agregar', 'files' => true, 'role' => 'form')) }}
        <h2><span>{{ $titulo_texto }}</span></h2>
        <div class="marginBottom2">
            <a class="volveraSeccion" href="{{URL::to('/'.Seccion::find($seccion_id) -> menuSeccion()->lang() -> url)}}"><i class="fa fa-caret-left"></i>{{ Lang::get('html.volver_a') }} {{ Seccion::find($seccion_id) -> menuSeccion()->lang() -> nombre }}</a>
        </div>

        <div class="row">
            @section('bloque-1')
            <!-- Título del Producto, Obra o Muestra -->
            <div class="col-md-6 divDatos divCargaTitulo">
                <h3>@section('titulo_nombre') Nombre @show</h3>
                <div class="form-group fondoDestacado">
                    <input class="form-control" type="text" name="titulo" placeholder="{{$placeholder_nombre or 'Ingrese un nombre'}}" required="true" maxlength="{{$max_length or ''}}">
                    <p class="infoTxt"><i class="fa fa-info-circle"></i>@section('info_nombre')No puede haber dos productos con igual nombre. Máximo {{ $max_length or '...' }} caracteres.@show</p>
                </div>
            </div>
            @show

            @yield('bloque-2')
            
            @yield('bloque-3')
            
            @section('bloque-4')
            <!-- Estado -->
            <div class="col-md-6 divDatos divEstado">
                <h3>Destacado (opcional)</h3>
                <div class="fondoDestacado">
                    @section('destacado-contenido')
                    @show
                </div>
            </div>
            @show
            
            @yield('mas-datos')
        </div><!--  -->

        <div class="row">
            <!-- Imágenes -->
            <div class="col-md-12 divDatos divCargaImg">
                <h3>{{ $titulo_modulo_imagen or '' }}</h3>
                <div class="fondoDestacado">
                    <h4>Nueva imagen</h4>
                    <p class="infoTxt"><i class="fa fa-info-circle"></i>La imagen original no debe exceder los 500kb de peso.</p>

                    @section('modulo_imagen')
                    
                    <input type="hidden" ng-model="url_public" ng-init="url_public = '{{URL::to('/')}}'">
                    
                    @include('imagen.modulo-imagen-angular-crop-horizontal-multiples')
                    <div class="row">
                        <div class="col-md-12" ng-show='imagenes_seleccionadas.length > 0'>
                            <h3>Imágenes cargadas</h3>
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
                    
                    @show
                </div>
            </div>
        </div>  
        
        @section('detalles-tecnicos')
        <div class="row">
            @section('columna-izquierda')
            <div class="col-md-6 divDatos">
                @section('cuerpo')
                <!-- Texto Descriptivo del Producto u obra -->
                <div class="divCargaTxtDesc">
                    <h3>@section('titulo-cuerpo') Detalles técnicos @show</h3>
                    <div class="divEditorTxt fondoDestacado">
                        <textarea id="texto" contenteditable="true" name="cuerpo"></textarea>
                    </div>
                </div>
                @show

                @section('archivos')
                <!-- PDF -->
                <div class="divCargaArchivosPDF">
                    <h3>Agregar archivos PDF</h3>
                    <div class="fondoDestacado">
                        @include('archivo.modulo-archivo-maxi')
                    </div>   
                </div>
                @show

                @section('videos')
                <!-- Videos -->
                <div class="divCargaVideos">
                    <h3>Videos</h3>
                    <div class="fondoDestacado">
                        @include('video.modulo-video-agregar')
                    </div>   
                </div>
                @show
            </div>
            @show

            @section('columna-derecha')
            <div class="col-md-6 divDatos">
                @yield('ubicacion')
            </div>
            @show
        </div>
        @show
        
        <div class="row">
            <div class="col-md-12">
                <div class="border-top">                
                    <input type="submit" value="Publicar" class="btn btn-primary marginRight5">
                    <a href="{{URL::to('/'.Seccion::find($seccion_id) -> menuSeccion()->lang() -> url)}}" class="btn btn-default">Cancelar</a>
                </div>
            </div>
        </div>

            @yield('hidden')
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