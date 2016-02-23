@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Producto destacado</h4>
    </div>
    {{ Form::open(array('url' => 'admin/producto/destacar')) }}
        <div class="modal-body">
            <input class="block anchoTotal marginBottom" type="text" name="precio" placeholder="Precio" required="true">
            {{Form::hidden('continue', $continue)}}
    {{Form::hidden('producto_id', $producto->id)}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    {{Form::close()}}
@stop

@section('footer')@stop