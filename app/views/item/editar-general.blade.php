@extends($project_name.'-master')

@section('contenido')
    @yield('funcs')
    <section class="container"  id="ng-app" ng-app="app">    
        <div ng-controller="ImagenMultiple" nv-file-drop="" uploader="uploader" filters="customFilter, sizeLimit">
        {{ Form::open(array('url' => $prefijo.'/admin/'.$modulo_pagina_nombre.'/editar', 'files' => true, 'role' => 'form', 'onsubmit' => 'return validatePrecioProd(this);')) }}
        <h2><span>{{ $titulo_texto }}</span></h2>
        <div class="marginBottom2">
            <a class="volveraSeccion" href="@if($seccion_next != 'null'){{URL::to('/'.Seccion::find($seccion_next) -> menuSeccion()->lang() -> url)}}@else{{URL::to('/')}}@endif"><i class="fa fa-caret-left"></i>{{ Lang::get('html.volver_a') }} @if($seccion_next != 'null'){{ Seccion::find($seccion_next) -> menuSeccion()->lang() -> nombre }}@else Home @endif</a>
        </div>

        <div class="row">
            @section('bloque-1')
            <!-- Título del Producto, Obra o Muestra -->
            <div class="col-md-6 divDatos divCargaTitulo">
                <h3>@section('titulo_nombre') Nombre @show</h3>
                <div class="form-group fondoDestacado">
                    <input class="form-control" type="text" name="titulo" placeholder="{{$placeholder_nombre or 'Ingrese un nombre'}}" required="true" maxlength="{{$max_length or ''}}" value="{{ $item->lang()->titulo }}">
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
        </div>

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


                        @if((count($item->imagen_destacada()) > 0) || (count($item->imagenes) > 0))

                        @endif
                        @if(count($item->imagen_destacada()) > 0)
                        <div class="row">
                            <div class="col-md-12 imgSeleccionadas">
                                <h4>Imágenes cargadas</h4>
                                <p class="infoTxt"><i class="fa fa-info-circle"></i>La primer imagen cargada se mostrará en el catálogo. Puede cambiar el orden arrastrando con el mouse. </p>
                            </div>
                            <div class="col-md-2 imgSelecDestacada">
                                <div class="thumbnail">
                                    <input type="hidden" name="imagen_crop_editar[]" value="{{$item->imagen_destacada()->id}}">
                                    <img class="marginBottom1" src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}" alt="{{$item->lang()->titulo}}">
                                    <input class="form-control" type="text" name="epigrafe_imagen_crop_editar[]" value="{{$item->imagen_destacada()->lang()->epigrafe}}">
                                    <i onclick="borrarImagenReload('{{ URL::to('admin/imagen/borrar') }}', '{{$item->imagen_destacada()->id}}');" class="fa fa-times-circle fa-lg"></i>
                                </div>
                            </div>
                            
                            @endif
                            @foreach($item->imagenes as $img)
                            <div class="imgSeleccionadas">
                                <div class="col-md-2">
                                    <div class="thumbnail">
                                        <input type="hidden" name="imagen_crop_editar[]" value="{{$img->id}}">
                                        <img class="marginBottom1" src="{{ URL::to($img->carpeta.$img->nombre) }}" alt="{{$item->lang()->titulo}}">
                                        <input class="form-control" type="text" name="epigrafe_imagen_crop_editar[]" value="{{$img->lang()->epigrafe}}">
                                        <i onclick="borrarImagenReload('{{ URL::to('admin/imagen/borrar') }}', '{{$img->id}}');" class="fa fa-times-circle fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div ng-repeat="img in imagenes_seleccionadas" class="imgSeleccionadas">
                                <div class="col-md-2">
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
                        @show
                    </div>
            </div>
        </div>  

        @section('detalles-tecnicos')
        <div class="row">
            @section('columna-izquierda')
            <div class="col-md-6 divDatos">
                @yield('cuerpo')
                
                @section('archivos')
                <!-- PDF -->
                <div class="divCargaArchivosPDF">
                    <h3>Agregar archivos PDF</h3>
                    <div class="fondoDestacado">
                        @include('archivo.modulo-archivo-maxi-editar')
                    </div>   
                </div>
                @show

                @section('videos')
                <!-- Videos -->
                <div class="divCargaVideos">
                    <h3>Videos</h3>
                    <div class="fondoDestacado">
                        @include('video.modulo-video-editar')
                    </div>   
                </div>
                @show
            </div>
            @show
            
            @section('columna-derecha')
                @yield('ubicacion')
            @show
        </div>
        @show

        <div class="row">
            <div class="col-md-12">
                <div class="border-top">
                    <input type="submit" value="Publicar" class="btn btn-primary marginRight5">
                    <a href="@if($seccion_next != 'null'){{URL::to('/'.Seccion::find($seccion_next) -> menuSeccion() -> lang() -> url)}}@else{{URL::to('/')}}@endif" class="btn btn-default">Cancelar</a>
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
