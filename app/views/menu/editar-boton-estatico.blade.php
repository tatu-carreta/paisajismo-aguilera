<h3>Editar Menú</h3>

{{ Form::open(array('url' => $prefijo.'/admin/menu/editar')) }}

    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="{{$menu->nombre}}" placeholder="Nombre del Menú" required="true">
    </div>

    @if($errors -> has('nombre'))
        <div class="alert alert-danger">
            @foreach($errors->get('nombre') as $error)
            *{{ $error }}<br>
            @endforeach
        </div>
    @endif
    <div class="floatRight">
        {{Form::hidden('id', $menu->menu_id)}}
        {{Form::submit('Guardar', array('class' => 'btn'))}}
    </div>
{{Form::close()}}
