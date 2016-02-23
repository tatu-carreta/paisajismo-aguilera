@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
<script src="{{URL::to('ckeditor/ckeditor.js')}}"></script>
        <script src="{{URL::to('ckeditor/adapters/jquery.js')}}"></script>
    <script>
        $(document).ready(function() {
            //$('textarea#cuerpo').ckeditor();
            CKEDITOR.replace( 'html', {
                    allowedContent: true,
                    fillEmptyBlocks: false
            } );
        });
    </script>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Carga y modificación de html</h4>
    </div>
    {{ Form::open(array('url' => $prefijo.'/admin/html/editar')) }}
        <div class="modal-body">
            <!--<input class="form-control" type="text" name="titulo" placeholder="Nombre de la Sección">-->
            <div class="divEditorTxt">
                <textarea id="html" contenteditable="true" class="" name="cuerpo">{{ $html->lang()->cuerpo }}</textarea>
            </div>
            {{Form::hidden('titulo', '')}}
            {{Form::hidden('html_id', $html->id)}}
            {{Form::hidden('id', $item->id)}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    {{Form::close()}}
@stop

@section('footer')@stop