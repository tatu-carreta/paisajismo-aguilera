@extends($project_name.'-master')

@section('head')
    @parent

@stop

@section('contenido')

    
    <section class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="presentacion">{{ Lang::get('html.titulo_inicio') }}</h2>
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
        <div class="row">
            <div class="col-md-12 moduloItem">
            <!-- SLIDE HOME -->
            @include($project_name.'-slide-home')
            </div>
        </div>
    </section>
@stop

@section('footer')
    @parent

@stop
