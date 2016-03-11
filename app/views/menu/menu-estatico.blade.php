@extends($project_name.'-master')

@section('contenido')
    @if(Auth::check())
        <script src="{{URL::to('js/popupFuncs.js')}}"></script>
    @endif
    <section class="container">
        <div class="cuerpoColor">
            <a href="{{URL::to('lo-que-nos-proponemos')}}" class="takano"><span>Takano</span></a>
            <div class="row">
                <div class="col-md-12 marginBottom1">
                    <h2>{{ $menu_basic->lang()->nombre }}</h2>
                </div>
            </div>
        
            @if(Auth::check())
                @if(Auth::user()->can("ordenar_seccion_estatica"))
                    @if(count($menu_basic->seccionesConItems()) >= 2)
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{URL::to('admin/seccion/ordenar-por-menu/'.$menu_basic->id)}}" class="btn pull-right popup-nueva-seccion iconoBtn-texto"><i class="fa fa-exchange fa-lg"></i>Ordenar secciones</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    @endif
                @endif
            @endif

            @if(Auth::check())
                @if(Auth::user()->can("ver_menu_estatico_admin"))
                <div class="row">
                    <div class="col-md-12 fondoDestacado text-center menuInsertContenido">       
                        @if(Auth::user()->can("agregar_slide"))
                           <a href="{{URL::to('admin/slide/agregar/'.$menu_basic->id.'/E')}}" class="btn btn-primary">Agregar slide</a>
                        @endif
                        @if(Auth::user()->can("agregar_galeria"))
                            <a href="{{URL::to('admin/galeria/agregar/'.$menu_basic->id)}}" class="btn btn-primary popup-nueva-seccion">Agregar galería</a>
                        @endif
                        @if(Auth::user()->can("agregar_texto"))
                            <a data-toggle="modal" href="{{URL::to('admin/texto/agregar/'.$menu_basic->id)}}" class="btn btn-primary popup-nueva-seccion">Agregar texto</a> 
                        @endif
                        @if(Auth::user()->can("agregar_html"))
                            <a data-toggle="modal" href="{{URL::to('admin/html/agregar/'.$menu_basic->id)}}" class="btn btn-primary popup-nueva-seccion">Agregar HTML</a>
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>
                @endif
            @endif

            <div class="row">
            @foreach($menu_basic -> secciones as $seccion)
                @if((count($seccion->items) > 0) || (count($seccion->slides) > 0))
                    @include('seccion.seccion-estatica')
                @endif
            @endforeach
            </div>
            
            <div class="clearfix"></div>
            @if(Auth::check())
                <div class="modal fade" id="nueva-seccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width: 900px !important;">
                        <div class="modal-content">
                            
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Include JQUERY CYCLE -->
    <script src="{{URL::to('js/jquery.cycle2.js')}}"></script>
    <script>
        $("#owl-slide").owlCarousel({
            autoPlay: 3000,
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem:true,
            transitionStyle : "fade"
        });
    </script>
@stop