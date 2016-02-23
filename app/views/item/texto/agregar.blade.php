@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
<script src="{{URL::to('ckeditor/ckeditor.js')}}"></script>
        <script src="{{URL::to('ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Carga y modificación de texto</h4>
    </div>
    {{ Form::open(array('url' => 'admin/texto/agregar')) }}
        <div class="modal-body">
            <!--<input class="form-control" type="text" name="titulo" placeholder="Nombre de la Sección">-->
            <div class="divEditorTxt">
                <textarea id="texto" contenteditable="true" class="" name="cuerpo"></textarea>
            </div>
            {{Form::hidden('titulo', '')}}
            {{Form::hidden('seccion_id', $seccion_id)}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    {{Form::close()}}
@stop

@section('footer')@stop