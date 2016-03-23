@extends($project_name.'-master')

@section('head')
    @parent

@stop

@section('contenido')

    
    <section class="container">
        <div class="row">
            <div class="col-md-12" style="display:none">
                <h2>{{ Lang::get('html.titulo_inicio') }}</h2>
            </div>  
        </div>


        <div class="row">
            <div class="col-md-12 banner">
                <!-- SLIDE HOME -->
                @include($project_name.'-slide-home')
            </div>  
        </div>

        <div class="row">
            <div class="col-md-7 presentacion">
                <p>Le proponemos una nueva manera de vivir su casa y su jardín: disfrutándolo, viéndolo crecer, sintiéndolo, percibiendo sus olores, aromas y formas y observando sus cambios constantes.
                Cuidando cada detalle, pensamos, diseñamos y creamos su espacio en armonía con la totalidad de su entorno.</p>
                <img src="{{ URL::to('images/firma.gif')}}" />
            </div> 
            <div class="col-md-5 curso">
                <h3>Curso de Paisajismo</h3>
                <p>El Curso es una aproximación al Paisajismo desde el Diseño y su propuesta consiste en aprender a planificar un espacio. Pensarlo, soñarlo y después, con las herramientas aprendidas diseñarlo.</p>
                <a href="{{URL::to('curso-de-paisajismo')}}" class="links">Más info</a> | <a href="{{URL::to('contacto')}}" class="links">Consultas</a> | <a href="http://www.facebook.com/profile.php?id=100002159523290&ref=ts" target="_blank" class="links">Novedades en Facebook</a>
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
    </section>
@stop

@section('footer')
    @parent

@stop
