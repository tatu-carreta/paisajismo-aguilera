<h3>Editar Categoría</h3>

{{ Form::open(array('url' => $prefijo.'/admin/categoria/editar')) }}

    <div class="form-group">
        <p><label for="nombre">Nombre</label></p>
        <input type="text" name="nombre" id="nombre" value="{{$categoria->nombre}}" placeholder="Nombre de la Categoría" required="true">
    </div>

    @if($errors -> has('nombre'))
        <div class="alert alert-danger">
            @foreach($errors->get('nombre') as $error)
                *{{ $error }}<br>
            @endforeach
        </div>
    @endif

    @if(Auth::user()->can("cambiar_categoria"))
        <div class="form-group">
            <p><label for="categoria_id">Categoría</label></p>
            <select class="form-control" name="categoria_id">
                <option value="">Seleccione Nueva Categoría</option>
                <option value="-1">Va a ser Padre</option>
                @foreach($categorias as $categoria1)
                    <option value="{{$categoria1->id}}">{{$categoria1->nombre}}</option>
                @endforeach
            </select>
        </div>

        @if($errors -> has('categoria_id'))
            <div class="alert alert-danger">
                @foreach($errors->get('categoria_id') as $error)
                    *{{ $error }}<br>
                @endforeach
            </div>
        @endif
    @endif
    
    <div class="floatRight">
    {{Form::hidden('id', $categoria->categoria_id)}}
    {{Form::submit('Guardar', array('class' => 'btn'))}}
    </div>
{{Form::close()}}
