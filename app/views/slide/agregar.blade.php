@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Carga y modificación de slide home</h4>
    </div>
    {{ Form::open(array('url' => 'admin/slide/agregar')) }}
        <div class="modal-body" id="ng-app" ng-app="app">
            <div class="row marginBottom2">
                <!-- Abre columna de imágenes -->
                <div class="col-md-12 cargaImg">
                    <div class="fondoDestacado">
                        <input type="hidden" ng-model="total_permitido" ng-init="total_permitido = 1">
                        @include('imagen.modulo-galeria-angular')
                    </div>
                </div>
            </div>  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
        {{Form::hidden('seccion_id', $seccion_id)}}
        {{Form::hidden('tipo', $tipo)}}
    {{Form::close()}}
    <script src="{{URL::to('js/angular-1.3.0.min.js')}}"></script>
    <script src="{{URL::to('js/angular-file-upload.js')}}"></script>
    <script src="{{URL::to('js/ng-img-crop.js')}}"></script>
    <script src="{{URL::to('js/controllers.js')}}"></script>
    <script src="{{URL::to('js/directives-galeria.js')}}"></script>
@stop

@section('footer')@stop
