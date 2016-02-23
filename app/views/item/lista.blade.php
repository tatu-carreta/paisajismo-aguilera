@extends($project_name.'-master')

@section('contenido')
<section class="container">
    <div class="row marketing">
        <h3>Crear Item</h3>

        {{ Form::open(array('url' => 'admin/item/agregar')) }}
        @if(Session::get('mensaje'))
        <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
        @endif
        <div class="form-group">
            {{Form::label('titulo', 'Título')}}
            {{Form::text('titulo', Input::old('titulo'), array('class' => 'form-control', 'placeholder' => 'Título del Item', 'autocomplete' => 'off'))}}
        </div>

        @if($errors -> has('titulo'))
        <div class="alert alert-danger">
            @foreach($errors->get('titulo') as $error)
            *{{ $error }}<br>
            @endforeach
        </div>
        @endif

        <div class="form-group">
            {{Form::label('descripcion', 'Descripción')}}
            {{Form::text('descripcion', Input::old('descripcion'), array('class' => 'form-control', 'placeholder' => 'Descripción del Item', 'autocomplete' => 'off'))}}
        </div>

        @if($errors -> has('descripcion'))
        <div class="alert alert-danger">
            @foreach($errors->get('descripcion') as $error)
            *{{ $error }}<br>
            @endforeach
        </div>
        @endif

        <div class="form-group">
            {{Form::label('seccion_id', 'Sección')}}
            <select class="form-control" name="seccion_id">
                <option value="">Seleccione una Sección</option>
                @foreach($secciones as $s)
                <option value="{{$s->id}}">{{$s->titulo}}</option>
                @endforeach
            </select>
        </div>

        @if($errors -> has('seccion_id'))
        <div class="alert alert-danger">
            @foreach($errors->get('seccion_id') as $error)
            *{{ $error }}<br>
            @endforeach
        </div>
        @endif

        {{Form::button('Cancelar', array('class' => 'volver btn btn-default'))}}
        {{Form::submit('Guardar', array('class' => 'btn btn-success'))}}

        {{Form::close()}}

        <br>

        <h3>Items</h3>

        <div class="list-group">
            @foreach($items as $i)
            <a href="{{URL::to('item/'.$i->url)}}" class="list-group-item col-lg-6">{{$i->titulo}}</a>
            <a href="{{URL::to('admin/item/editar/'.$i->id)}}" class="col-lg-3 btn btn-lg btn-primary">Editar</a>
            <a href="#" onclick="borrarData('item/borrar', '{{$i->id}}');" class="col-lg-3 btn btn-lg btn-danger">Borrar</a>
            @endforeach
        </div>

    </div>
</section>

@stop