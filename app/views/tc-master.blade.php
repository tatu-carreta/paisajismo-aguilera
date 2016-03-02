<!Doctype html>
<html lang="es">
    <head>
        @section('head')
        <meta charset="UTF-8">
        <title>{{ Lang::get('html.head.title') }}</title>
        
        <!-- abre LINK -->
        <link href="favicon.ico" rel="shortcut icon">
        <link href='https://fonts.googleapis.com/css?family=Alegreya+Sans:400,300italic,300,400italic,500italic,500,700,800,700italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet' type='text/css'>
        <meta name="description" content="{{ Lang::get('html.head.description') }}">
        <meta name="Keywords" content="{{ Lang::get('html.head.keywords') }}">
        <meta property="og:image" content="" />
        <meta name="viewport" content="width = device-width, initial-scale=1, maximum-scale=1">
        
        <!-- B O O T S T R A P -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="{{URL::to('bootstrap-3.3.4-dist/css/bootstrap.min.css')}}">
        
        <link rel="stylesheet" type="text/css" href="{{URL::to('css/jquery-ui.min.css')}}">
        <link rel="stylesheet" href="{{URL::to('font-awesome-4.2.0/css/font-awesome.min.css')}}">
        <!-- <link rel="stylesheet" href="{{URL::to('css/flexslider.css')}}">-->
        <link rel="stylesheet" href="{{URL::to('css/owl.carousel.css')}}">
        <link rel="stylesheet" href="{{URL::to('css/owl.theme.css')}}">
        <link rel="stylesheet" href="{{URL::to('source/jquery.fancybox.css')}}">
        <!--<link rel="stylesheet" type="text/css" href="{{URL::to('css/styleSlideHome.css')}}" />-->
        <!--<link rel="stylesheet" type="text/css" href="{{URL::to('css/base.css')}}">-->
        <link rel="stylesheet" type="text/css" href="{{URL::to('css/base_2.css')}}"> 
        <link rel="stylesheet" type="text/css" href="{{URL::to('css/styles.css')}}">
        
        <!-- M O D E R N I Z R -->
        <script src="{{URL::to('js/modernizr.custom.05470.js')}}"></script>
        
        <!-- abre SCRIPT -->
        <script src="{{URL::to('js/jquery-1.11.2.min.js')}}"></script>
        <script src="{{URL::to('js/jquery-ui.min.js')}}"></script>
        <script src="{{URL::to('js/funcs.js')}}"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script src="{{URL::to('bootstrap-3.3.4-dist/js/bootstrap.min.js')}}"></script>
        
        <!-- Include Fancybox -->
        <script src="{{URL::to('source/jquery.fancybox.js')}}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".fancybox").fancybox();
            });
        </script>

        <!-- Include MyTooltips -->
        <script src="{{URL::to('js/jquery.style-my-tooltips.js')}}"></script>
        <script type="text/javascript">  
            $().ready(function() {  
                //applies to all elements with title attribute. Change to ".class[title]" to select only elements with specific .class and title
                $("[title]").style_my_tooltips({ 
                    tip_follows_cursor: "on", //on/off
                    tip_delay_time: 10 //milliseconds
                });  
            });  
        </script>
        @show
    </head>
    <body>
        @section('header')
            @include('analyticstracking')
            <!-- abre H E A D E R -->
            @if(Auth::check())
            <div class="headerAdmin">
                @if((Auth::user()->hasRole('Superadmin')) || (Auth::user()->hasRole('Administrador')))
                <div class="divAdministrar">
                    @if(Auth::user()->can("exportar_clientes_c"))
                        <a href="{{URL::to('admin/exportar-clientes')}}" class="btnCalado"><i class="fa fa-pencil fa-lg"></i>Exportar Clientes</a>
                    @endif
                    @if(Auth::user()->can("exportar_clientes"))
                        <a href="{{URL::to('admin/exportar-personas')}}" class="btnCalado"><i class="fa fa-pencil fa-lg"></i>{{ Lang::get('menu_admin.exportar_personas') }}</a>
                    @endif
                    @if(Auth::user()->can("ver_menu_admin"))
                        <a href="{{URL::to('admin/menu')}}" class="btnCalado"><i class="fa fa-pencil fa-lg"></i>{{ Lang::get('menu_admin.menu') }}</a>
                    @endif
                    @if(Auth::user()->can("ver_item_admin"))
                        <a href="{{URL::to('admin/item')}}" class="btnCalado"><i class="fa fa-pencil fa-lg"></i>{{ Lang::get('menu_admin.items') }}</a>
                    @endif
                    @if(Auth::user()->can("ver_seccion_admin"))
                        <a href="{{URL::to('admin/seccion')}}" class="btnCalado"><i class="fa fa-pencil fa-lg"></i>{{ Lang::get('menu_admin.secciones') }}</a>
                    @endif
                    @if(Auth::user()->can("agregar_slide"))
                        <a href="{{URL::to('admin/slide/agregar/8/I')}}" class="btnCalado"><i class="fa fa-pencil fa-lg"></i>{{ Lang::get('menu_admin.slide_home') }}</a>
                    @endif
                    @if(Auth::user()->can("ver_marca_admin"))
                        <a href="{{URL::to('admin/marca')}}" class="btnCalado"><i class="fa fa-pencil fa-lg"></i>{{ Lang::get('menu_admin.marcas') }}</a>
                    @endif
                </div>
                @endif

                @if(true)
                <div class="divSalir">
                    <span class="nameAdmin btnCalado"><i class="fa fa-user fa-lg marginRight5"></i>{{Auth::user()->perfil()->name}}</span>
                    <a href="{{URL::to('logout')}}" class="btnCalado"><i class="fa fa-share fa-lg"></i>{{ Lang::get('menu_admin.salir') }}</a>
                </div>
                @else
                <div class="divSalir">
                    <a href="{{URL::to('login')}}" class="btnCalado"><i class="fa fa-share fa-lg"></i>Ingresar</a>
                </div>
                @endif
            </div>
            @endif
            <!-- H E A D E R -->
            <header>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="logo pull-left"><a href="{{URL::to('/')}}" ><img src="{{ URL::to('images/patricia-aguilera.gif')}}" /><span>Paisajismo Aguilera</span></a></h1>
                            <a class="facebook" href=""><span>Facebook</span></a>
                             <!-- B T N   C A R R I T O -->
                             <?php
                             /*
                            <a href="{{URL::to('carrito')}}" class="btnCarrito active"><span>{{ Lang::get('html.presupuesto') }}: {{Cart::count(false)}}</span><i class="fa fa-shopping-cart fa-lg"></i></a>
                            */
                             ?>
                            <?php
                            /*
                            <!-- VENTANA CARRITO -->
                            @if(Session::has('producto_carrito_subido'))
                                @if(Session::get('producto_carrito_subido'))
                                    <!-- ventana Carrito -->
                                    <div id="ventanaCarrito" class="divEmergente">
                                        <div class="triang"></div>
                                        <a href="{{URL::to('carrito')}}" class="">
                                        {{--<img class="imgArtPedido" src="@if(!is_null(Session::get('producto_carrito')->item()->imagen_destacada())){{ URL::to(Session::get('producto_carrito')->item()->imagen_destacada()->carpeta.Session::get('producto_carrito')->item()->imagen_destacada()->nombre) }}@else{{URL::to('images/sinImg.gif')}}@endif" alt="">--}}
                                        <i class="fa fa-shopping-cart fa-2x"></i>
                                        <p>{{ Lang::get('html.ventana_producto') }}</p>
                                        {{--<span>Cod: {{ Session::get('producto_carrito')->item()->titulo }}</span>--}}
                                        </a>
                                        <div class="cerrarEmergente cerrarVentanaCarrito"><i class="fa fa-times fa-lg"></i></div>
                                    </div>
                                @endif
                            @endif
                             * 
                             */
                            ?>
                            
                            <!-- N A V -->
                            @include('menu.'.$project_name.'-desplegar-menu')

                            <div class="clearfix"></div> 
                        </div>
                    </div>
                </div>
            </header>  
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Lang::get('locales.label') }} <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    @foreach(Lang::get('locales.option') as $mKey => $mLanguage)
                        <li>{{ HTML::linkAction('BaseController@setLocale', $mLanguage, array($mKey, $type, $ang)) }}</li>
                    @endforeach
                </ul>
            </li>

            @yield('slide-estatico') 

        @show
        
        <!-- abre S E C T I O N -->
        @yield('contenido')

        @section('footer')
        <!-- abre F O O T E R -->
        <footer class="tarjetas">
            @section('tarjetas')
                @if(!Auth::check())
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="copy"><p><a href="mailto:patricia.aguilera.ar@gmail.com">patricia.aguilera.ar@gmail.com</a> / 54 221 <strong>15 589-8285</strong> / 54 221 <strong>471-4668</strong> / Gonnet - La Plata - Buenos Aires - Argentina</p></div>
                                <p class="firmalaura">{{ Lang::get('html.copyright') }} <a href="http://www.laurachuburu.com.ar/" target="_blank">www.laurachuburu.com.ar</a></p>
                            </div>
                        </div>

                    </div>
                @endif
            @show
        </footer>
        <!--
        <script src="{{URL::to('ckeditor/ckeditor.js')}}"></script>
        <script src="{{URL::to('ckeditor/adapters/jquery.js')}}"></script>-->
        <script src="{{URL::to('js/jquery.lazyload.min.js')}}"></script>
        <!--<script src="{{URL::to('js/jquery-ui.min.js')}}"></script>
        <!--<script src="{{URL::to('js/div-ventana-carrito.js')}}"></script>-->
        
        <script>
            $(function () {
                $("img.lazy").lazyload({
                    effect: "fadeIn"
                });
            });
        </script>

        <!-- Div alerta  -->
        @include($project_name.'-div-alerta')
        
        <!-- Div loading  -->
        @include($project_name.'-div-loading')
        
        @show
        
    </body>
</html>