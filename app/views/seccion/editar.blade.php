@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Carga y modificación de secciones</h4>
    </div>
    {{ Form::open(array('url' => $prefijo.'/admin/seccion/editar')) }}
        <div class="modal-body">
            <input class="form-control" type="text" name="titulo" value="{{ $seccion->lang()->titulo }}" placeholder="Nombre de la Sección">
            {{Form::hidden('id', $seccion->id)}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    {{Form::close()}}
            

@stop

@section('footer')@stop