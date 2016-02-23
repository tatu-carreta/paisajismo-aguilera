@extends($project_name.'-master')

@section('contenido')
<section class="container"  id="ng-app" ng-app="app">
    <h2 class="marginBottom2"><span>Editar slide de home</span></h2>
    {{ Form::open(array('url' => 'admin/slide/agregar')) }}
        <div class="row marginBottom2">
            <!-- Abre columna de imágenes -->
            <div class="col-md-8 cargaImg">
                <div class="fondoDestacado">
                    <input type="hidden" ng-model="total_permitido" ng-init="total_permitido = 4">
                    @include('imagen.modulo-galeria-angular')
                </div>
            </div>
        </div>  
        <div class="border-top">
            <button type="submit" class="btn btn-primary marginRight5">Publicar</button>
            <button type="button" class="btn btn-default" onclick="window.history.back();">Cancelar</button>
        </div>

        
        {{Form::hidden('seccion_id', $seccion_id)}}
        {{Form::hidden('tipo', $tipo)}}
    {{Form::close()}}
</section>
@stop

@section('footer')

    @parent

    <script src="{{URL::to('js/angular-1.3.0.min.js')}}"></script>
    <script src="{{URL::to('js/angular-file-upload.js')}}"></script>
    <script src="{{URL::to('js/ng-img-crop.js')}}"></script>
    <script src="{{URL::to('js/controllers.js')}}"></script>
    <script src="{{URL::to('js/directives-galeria.js')}}"></script>
    
    <script src="{{URL::to('ckeditor/ckeditor.js')}}"></script>
        <script src="{{URL::to('ckeditor/adapters/jquery.js')}}"></script>

@stop