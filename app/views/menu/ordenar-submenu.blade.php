@extends($project_name.'-master')

@section('head')@stop
@section('header')@stop

@section('contenido')
    <div>
        <script>
            $(function () {
                $('.sortable').sortable();
            });
        </script>
        {{ Form::open(array('url' => 'admin/menu/ordenar-submenu', 'id' => 'ordenar')) }}

            <ul class="sortable">
                @foreach($menus_ordenar as $menu)
                    @if(($menu->orden != -1) && ($menu->orden != 99))
                        <li><span><b>Menú:</b> <strong>{{ $menu->id }}</strong> {{ $menu -> nombre }}</span><input type="hidden" name="orden[]" value="{{$menu->id}}"></li><br>
                    @endif
                @endforeach
            </ul>
            {{Form::hidden('menu_id', $menu_id)}}
            <div class="floatRight">
                <a onclick="cancelarPopup('orden');" class="btnGris marginRight5">Cancelar</a>
                <input type="submit" value="Guardar" class="btn">
            </div>

        {{Form::close()}}
    </div>
@stop

@section('footer')@stop