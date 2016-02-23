@extends($project_name.'-master')

@section('contenido')
<script>
    $(function(){
        $(".selectMarca").change(function () {
            var id = $(".selectMarca option:selected").val();
            if (id != "")
            {
                $.post("{{URL::to('admin/marca/imagen')}}", {'marca_id': id}, function (data) {
                    $(".marca_imagen_preview").html(data);
                });
            }
            else
            {
                $(".marca_imagen_preview").html("");
            }
        });
    });
    
</script>
<script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
<script src="{{URL::to('js/producto-funcs.js')}}"></script>
<section class="container"  id="ng-app" ng-app="app">    
    <div ng-controller="ImagenMultiple" nv-file-drop="" uploader="uploader" filters="customFilter, sizeLimit">
    {{ Form::open(array('url' => 'admin/producto/editar', 'files' => true, 'role' => 'form', 'onsubmit' => 'return validatePrecioProd(this);')) }}
        <h2><span>Editar producto</span></h2>
        <div class="marginBottom2">
            <a class="volveraSeccion" href="@if($seccion_next != 'null'){{URL::to('/'.Seccion::find($seccion_next) -> menuSeccion() -> url)}}@else{{URL::to('/')}}@endif"><i class="fa fa-caret-left"></i>Volver a @if($seccion_next != 'null'){{ Seccion::find($seccion_next) -> menuSeccion() -> nombre }}@else Home @endif</a>
        </div>

        <div class="row">
            <!-- Título del Producto, Obra o Muestra -->
            <div class="col-md-6 divDatos divCargaTitulo">
                <h3>Nombre de producto</h3>
                <div class="form-group fondoDestacado">
                    <input class="form-control" type="text" name="titulo" placeholder="Código" required="true" maxlength="9" value="{{ $item->titulo }}">
                    <p class="infoTxt"><i class="fa fa-info-circle"></i>No puede haber dos productos con igual nombre. Máximo 9 caracteres.</p>
                </div>
            </div>
        
            <!-- Marca -->
            <div class="col-md-6 divDatos divCargaMarca">
                <h3>Marca</h3>
                <div class="form-group fondoDestacado">
                    <div class="row">
                        <div class="col-md-9">
                            <select class="form-control selectMarca" name="marca_principal" id="marca_principal">
                                <option value="">Seleccione una Marca</option>
                                @foreach($marcas_principales as $marca)
                                    <option value="{{$marca->id}}" @if(!is_null($producto->marca_principal())) @if($marca->id == $producto->marca_principal()->id) selected @endif @endif>{{$marca->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="marca_imagen_preview"></div>
                        </div>
                    </div>
                    
                    
                    <!-- <input class="form-control" type="text" name="titulo" placeholder="Código" required="true" maxlength="9"> -->
                    <p class="infoTxt"><i class="fa fa-info-circle"></i>...</p>
                </div>
            </div>
            
            <!-- Precio -->
            <div class="col-md-6 divDatos divCargaPrecio">
                <h3>Precio</h3>
                <div class="form-group fondoDestacado">
                    <input id="" class="form-control inputWidth60 precio-number" type="text" name="precio" value="{{ $producto->precio(2) }}">
                    <!-- <input class="form-control" type="text" name="titulo" placeholder="Código" required="true" maxlength="9"> -->
                    <p class="infoTxt"><i class="fa fa-info-circle"></i>...</p>
                </div>
            </div>
            
            <!-- Estado -->
            <div class="col-md-6 divDatos divEstado">
                <h3>Destacado (opcional)</h3>
                <div class="fondoDestacado">
                    <div class="radio divEstadoNuevo">
                        <label>
                            <input id="" class="" type="checkbox" name="item_destacado" value="N" @if($item->producto()->nuevo()) checked="true" @endif>
                            <i class="fa fa-tag prodDestacado fa-lg"></i>
                            Nuevo
                        </label>
                    </div>

                    <div class="divEstadoOferta">
                        <div class="checkEstado">
                            <div class="radio">
                                <label>
                                    <input id="" class=" precioDisabled" type="checkbox" name="item_destacado" value="O" @if($item->producto()->oferta()) checked="true" @endif>
                                    <i  class="fa fa-usd prodDestacado fa-lg"></i>
                                    Oferta
                                </label>
                            </div>
                        </div>
                        <div class="divPrecio">
                            <label for="" >
                                <span>Precio después $ </span><input id="" class="form-control inputWidth60 precioAble1 precio-number" type="text" name="precio_actual" value="{{ $producto->precio(2) }}">
                            </label>
                        </div>
                        <div class="divPrecio">
                            <label for="" >
                                <span>Precio antes $ </span><input id="" class="form-control inputWidth60 precioAble1 precio-number" type="text" name="precio_antes" value="{{ $producto->precio(1) }}">
                            </label>
                        </div>   
                        <div class="clearfix"></div>
                    </div>
                    <!-- <p class="infoTxt"><i class="fa fa-info-circle"></i>Los productos NUEVOS y las OFERTAS se muestran también en la home.</p> -->
                </div>
            </div>
        </div>
            
        <div class="row">
            <!-- Imágenes -->
            <div class="col-md-12 divDatos divCargaImg">
                <h3>Imágenes del producto</h3>
                    <div class="fondoDestacado">
                        <h4>Nueva imagen</h4>
                        <p class="infoTxt"><i class="fa fa-info-circle"></i>La imagen original no debe exceder los 500kb de peso.</p>
                        
                        <input type="hidden" ng-model="url_public" ng-init="url_public = '{{URL::to('/')}}'">
                        @include('imagen.modulo-imagen-angular-crop-horizontal-multiples')
                    

                        @if((count($item->imagen_destacada()) > 0) || (count($item->imagenes) > 0))

                        @endif
                        @if(count($item->imagen_destacada()) > 0)
                        <div class="row">
                            <div class="col-md-12 imgSeleccionadas">
                                <h4>Imágenes cargadas</h4>
                                <p class="infoTxt"><i class="fa fa-info-circle"></i>La primer imagen cargada se mostrará en el catálogo. Puede cambiar el orden arrastrando con el mouse. </p>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="thumbnail">
                                            <input type="hidden" name="imagen_crop_editar[]" value="{{$item->imagen_destacada()->id}}">
                                            <img class="marginBottom1" src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}" alt="{{$item->titulo}}">
                                            <input class="form-control" type="text" name="epigrafe_imagen_crop_editar[]" value="{{$item->imagen_destacada()->epigrafe}}">
                                            <i onclick="borrarImagenReload('{{ URL::to('admin/imagen/borrar') }}', '{{$item->imagen_destacada()->id}}');" class="fa fa-times-circle fa-lg"></i>
                                        </div>
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
                    </div>
            </div>
        </div>  

        <div class="row">
            <div class="col-md-6 divDatos">
                <!-- Texto Descriptivo del Producto u obra -->
                <div class="divCargaTxtDesc">
                    <h3>Detalles técnicos</h3>
                    <div class="divEditorTxt fondoDestacado">
                        <textarea id="texto" contenteditable="true" name="cuerpo">{{ $item->producto()->cuerpo }}</textarea>
                    </div>
                </div>

                <!-- PDF -->
                <div class="divCargaArchivosPDF">
                    <h3>Agregar archivos PDF</h3>
                    <div class="fondoDestacado">
                        @include('archivo.modulo-archivo-maxi-editar')
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
                    </div>   
                </div>
            </div>
            @if($seccion_next != 'null')
            <div class="col-md-6 divDatos">
                <!-- Indicar Sección a la que pertenece el producto -->
                <div class="divModIndicarSeccion">
                    <h3>Ubicación</h3>
                    <div class="modIndicarSeccion">
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
                </div>
            </div>
            @else
                @foreach($item->secciones as $seccion)
                    <input type="hidden" name="secciones[]" value="{{$seccion->id}}">
                @endforeach
            @endif
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="border-top">
                    <input type="submit" value="Publicar" class="btn btn-primary marginRight5">
                    <a href="@if($seccion_next != 'null'){{URL::to('/'.Seccion::find($seccion_next) -> menuSeccion() -> url)}}@else{{URL::to('/')}}@endif" class="btn btn-default">Cancelar</a>
                </div>
            </div>
        </div>


        {{Form::hidden('continue', $continue)}}
        {{Form::hidden('id', $item->id)}}
        {{Form::hidden('producto_id', $producto->id)}}
        {{Form::hidden('descripcion', '')}}
        {{Form::hidden('tipo_precio_id[]', '2')}}
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
