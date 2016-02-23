@extends($project_name.'-master')

@section('head')
    @parent
    <!-- Include OWL CARROUSEL -->
    <script src="{{URL::to('js/owl.carousel.js')}}"></script>

    <script>
    $(document).ready(function(){
        setTimeout(function() {
            $(".carousel-oculto").removeClass("carousel-oculto");
            $("#ancla").click();
        }, 5);
    });
    </script>
    
@stop

@section('contenido')

    @if(Session::has('anclaProd'))
        <a id="ancla" href="{{ Session::get('anclaProd') }}" style="display: none;">Ancla</a>
    @endif

    <section class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="presentacion">{{ Lang::get('html.titulo_inicio') }}</h2>
            </div>  
        </div>
        @if(count($items_home) > 0)
        <div class="row carrouselProdHome carousel-oculto">
            <div id="owl-demo-prod">
                
                    <!-- PRODUCTOS DESTACADOS -->
                    @foreach($items_home as $item)
                        <div class="item"  id="Pr{{$item->producto()->id}}">
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    @if(Auth::check())
                                        <div class="iconos">
                                            <span class="pull-left">
                                                @if(!$item->producto()->nuevo())
                                                    @if(Auth::user()->can("destacar_item"))
                                                        <a class="btn @if($item->producto()->oferta()) disabled @endif" @if(!$item->producto()->oferta()) onclick="destacarItemSeccion('{{URL::to('admin/producto/nuevo')}}', 'null', '{{$item->id}}');" @endif ><i class="fa fa-tag fa-lg"></i>{{ Lang::get('html.nuevo') }}</a>
                                                    @endif
                                                @else
                                                    @if(Auth::user()->can("quitar_destacado_item"))
                                                        <a class="btn" onclick="destacarItemSeccion('{{URL::to('admin/item/quitar-destacado')}}', 'null', '{{$item->id}}');" ><i class="fa fa-tag prodDestacado fa-lg"></i>{{ Lang::get('html.nuevo') }}</a>
                                                    @endif
                                                @endif
                                                @if(!$item->producto()->oferta())
                                                    @if(Auth::user()->can("destacar_item"))
                                                        <a href="{{URL::to('admin/producto/oferta/'.$item->producto()->id.'/null/home')}}" class="btn popup-nueva-seccion"><i  class="fa fa-shopping-cart fa-lg"></i>{{ Lang::get('html.oferta') }}</a>
                                                    @endif
                                                @else
                                                    @if(Auth::user()->can("quitar_destacado_item"))
                                                        <a onclick="destacarItemSeccion('{{URL::to('admin/item/quitar-destacado')}}', 'null', '{{$item->id}}');" class="btn"><i class="fa fa-shopping-cart prodDestacado fa-lg"></i>{{ Lang::get('html.oferta') }}</a>
                                                    @endif
                                                @endif
                                            </span>
                                            <span class="pull-right editarEliminar">
                                                @if(Auth::user()->can("editar_item"))
                                                    <a href="{{URL::to('admin/producto/editar/'.$item->producto()->id.'/home/null')}}" data='null'><i class="fa fa-pencil fa-lg"></i></a>
                                                @endif
                                                @if(Auth::user()->can("borrar_item"))
                                                    <a onclick="borrarData('{{URL::to('admin/item/borrar')}}', '{{$item->id}}');"><i class="fa fa-times fa-lg"></i></a>
                                                @endif
                                            </span>
                                            <div class="clearfix"></div>
                                        </div>
                                    @endif
                                    
                                    <a class="fancybox" href="@if(!is_null($item->imagen_destacada())){{URL::to($item->imagen_destacada()->ampliada()->carpeta.$item->imagen_destacada()->ampliada()->nombre)}}@else{{URL::to('images/sinImg.gif')}}@endif" title="{{$item->lang()->titulo}} @if(!is_null($item->imagen_destacada())){{ $item->imagen_destacada()->ampliada()->lang()->epigrafe }}@endif" rel='group'> 
                                        <div class="divImgItem">
                                            <img class="lazy" src="@if(!is_null($item->imagen_destacada())){{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}@else{{URL::to('images/sinImg.gif')}}@endif" alt="{{$item->lang()->titulo}}">
                                            @if($item->producto()->oferta())
                                                <span class="bandaOfertas">{{ Str::upper(Lang::get('html.oferta')) }}: ${{$item->producto()->precio(2)}} <span>({{ Str::lower(Lang::get('html.oferta_antes')) }}: ${{$item->producto()->precio(1)}})</span></span>
                                            @elseif($item->producto()->nuevo())
                                                <span class="bandaNuevos">{{ Str::upper(Lang::get('html.nuevo')) }}</span>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="bandaInfoProd @if($item->producto()->nuevo()) nuevos @elseif($item->producto()->oferta()) ofertas @endif ">
                                        <span class="pull-left">{{ $item->lang()->titulo }}</span>
                                        @if(!Auth::check())
                                            @if($c = Cart::search(array('id' => $item->producto()->id)))
                                            <a href="{{URL::to('carrito/borrar/'.$item->producto()->id.'/'.$c[0].'/home/h')}}" class="carrito boton-presupuestar btn pull-right" onclick="ShowLoading()"> <!--onclick="ShowLoading()"--><i class="fa fa-check-square-o"></i>{{ Lang::get('html.presupuestar') }}</a>
                                            @else
                                            <a href="{{URL::to('carrito/agregar/'.$item->producto()->id.'/home/h')}}" class="btn boton-presupuestar pull-right" onclick="ShowLoading()"><!--onclick="ShowLoading()"--><i class="fa fa-square-o"></i>{{ Lang::get('html.presupuestar') }}</a>
                                            @endif
                                        @endif
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach 
            </div>
        </div>
        
            @if(Auth::check())
                <script src="{{URL::to('js/popupFuncs.js')}}"></script>

                <div class="modal fade" id="nueva-seccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                        </div>
                    </div>
                </div>
            @endif
        @endif
        {{--
        <div class="row">
            <div class="col-md-12 moduloItem">
            <!-- SLIDE HOME -->
            @include($project_name.'-slide-home')
            </div>
        </div>
        --}}
    </section>
@stop

@section('footer')
    @parent
    <script>
        $(document).ready(function() {
          $("#owl-demo-prod").owlCarousel({
              items : 4,
              itemsDesktop : [1199,4],
              itemsDesktopSmall : [979,3],
              mouseDrag  : false
          });
        });
    </script>
@stop
