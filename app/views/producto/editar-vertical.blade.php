@extends($project_name.'-master')

@section('contenido')
<script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
<script src="{{URL::to('js/producto-funcs.js')}}"></script>
<section class="container">    
        {{ Form::open(array('url' => 'admin/producto/editar', 'files' => true, 'role' => 'form', 'onsubmit' => 'return validatePrecioProd(this);')) }}
        <h2><span>Editar producto</span></h2>
        <div class="marginBottom2">
            <a class="volveraSeccion" href="@if($seccion_next != 'null'){{URL::to('/'.Seccion::find($seccion_next) -> menuSeccion() -> url)}}@else{{URL::to('/')}}@endif"><i class="fa fa-caret-left"></i>Volver a @if($seccion_next != 'null'){{ Seccion::find($seccion_next) -> menuSeccion() -> nombre }}@else Home @endif</a>
        </div>
        <div class="row datosProducto marginBottom2">
            <!-- Abre columna de descripción de Producto -->
            <div class="col-md-6">

                <!-- Nombre del producto -->
                <div class="divCargaTitProd">
                    <h3>Código del producto</h3>
                    <div class="form-group">
                        <input class="form-control" type="text" name="titulo" placeholder="Código" required="true" maxlength="9" value="{{ $item->titulo }}">
                        <p class="infoTxt"><i class="fa fa-info-circle"></i>No puede haber dos productos con igual código. Máximo 9 caracteres.</p>
                    </div>
                </div>

                <!-- Estado  -->
                <div class="divEstado">
                <h3>Estado</h3>
                    <div class="divEstadoNuevo">
                        <div class="radio">
                            <label>
                                <input id="" class="" type="checkbox" name="item_destacado" value="N" @if($item->producto()->nuevo()) checked="true" @endif>
                                 <i class="fa fa-tag prodDestacado fa-lg"></i>
                                Nuevo
                            </label>
                        </div>
                    </div>
                    <div class="divEstadoOferta">
                        <div class="checkEstado">
                            <div class="radio">
                                <label>
                                    <input id="" class="precioDisabled" type="checkbox" name="item_destacado" value="O" @if($item->producto()->oferta()) checked="true" @endif>
                                    <i  class="fa fa-usd prodDestacado fa-lg"></i>
                                    Oferta
                                </label>
                            </div>
                        </div>
                        <div class="divPrecio">
                            <label for="" >
                                <span>Precio después $ </span><input id="" class="form-control inputWidth60 precioAble1 precio-number" type="text" name="precio_antes" value="">
                            </label>
                        </div>
                        <div class="divPrecio">
                            <label for="" >
                                <span>Precio antes $ </span><input id="" class="form-control inputWidth60 precioAble1 precio-number" type="text" name="precio_actual" value="">
                            </label>
                        </div>   
                        <div class="clearfix"></div>
                    </div>
                    <p class="infoTxt"><i class="fa fa-info-circle"></i>Los productos NUEVOS y las OFERTAS se muestran también en la home.</p>
                </div>

                <!-- Indicar Sección a la que pertenece el producto  -->
                <div class="divModIndicarSeccion">
                    @if($seccion_next != 'null')
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
                    @else

                    @foreach($item->secciones as $seccion)
                        <input type="hidden" name="secciones[]" value="{{$seccion->id}}">
                    @endforeach
                @endif

                </div>
            </div><!--cierra columna datos de producto-->

            <!-- Abre columna de imágenes -->
           
                <h3>Imagen principal</h3>
                <div class="col-md-6 divCargarImg">
                    <h4>Carga y recorte de la imagen</h4>
                    <p class="infoTxt"><i class="fa fa-info-circle"></i>
                    La imagen original puede ser vertical u horizontal pero no debe exceder los 500kb de peso.</p>
                @if(!is_null($item->imagen_destacada()))

                    <div class="divCargaImgProducto">
                        <div class="divImgCargada">
                            <img alt="{{$item->titulo}}"  src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}">
                            <i onclick="borrarImagenReload('{{ URL::to('admin/imagen/borrar') }}', '{{$item->imagen_destacada()->id}}');" class="fa fa-times-circle fa-lg"></i>
                        </div>
                        <input type="hidden" name="imagen_portada_editar" value="{{$item->imagen_destacada()->id}}">
                        <input class="form-control" type="text" name="epigrafe_imagen_portada_editar" placeholder="Ingrese una descripción de la foto" value="{{ $item->imagen_destacada()->epigrafe }}">
                    </div>
                @else
                    @include('imagen.modulo-imagen-angular-crop')
                @endif
                
            </div>

            <div class="clear"></div>
            <!-- cierran columnas -->


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
    </section>
@stop

@section('footer')

    @parent

    <script src="{{URL::to('js/angular-1.3.0.min.js')}}"></script>
    <script src="{{URL::to('js/angular-file-upload.js')}}"></script>
    <script src="{{URL::to('js/ng-img-crop.js')}}"></script>
    <script src="{{URL::to('js/controllers.js')}}"></script>

@stop
