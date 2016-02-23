@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
    <script>
        $(function () {
            $('.sortable').sortable();
        });
    </script>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Carga y modificación de secciones</h4>
    </div>
    {{ Form::open(array('url' => 'admin/seccion/ordenar-por-menu', 'id' => 'ordenar')) }}
        <div class="modal-body">
            <ul class="sortable">
                @foreach($secciones as $seccion)
                <li id="{{$seccion->id}}"><span><b>Sección:</b> <strong>{{ $seccion->id }}</strong> {{ $seccion -> titulo }}</span><input type="hidden" name="orden[]" value="{{$seccion->id}}"></li><br>
                @endforeach
            </ul>
            {{Form::hidden('menu_id', $menu->id)}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    {{Form::close()}}
@stop

@section('footer')@stop