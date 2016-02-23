@extends($project_name.'-master')

@section('contenido')
<section class="container">
    <h3>Agregar Permiso</h3>

    {{ Form::open(array('url' => 'admin/permiso/agregar')) }}
    @if (Session::has('mensaje'))
        <div class="divAlerta ok alert-success">{{ Session::get('mensaje') }}<i onclick="" class="cerrarDivAlerta fa fa-times fa-lg"></i></div>
    @endif
    <div class="form-group">
        {{Form::label('nombre', 'Título')}}
        {{Form::text('nombre', Input::old('nombre'), array('class' => 'form-control', 'placeholder' => 'Nombre del Permiso', 'autocomplete' => 'off'))}}
    </div>

    @if($errors -> has('nombre'))
    <div class="alert alert-danger">
        @foreach($errors->get('nombre') as $error)
        *{{ $error }}<br>
        @endforeach
    </div>
    @endif

    {{Form::button('Cancelar', array('class' => 'volver btn btn-default'))}}
    {{Form::submit('Guardar', array('class' => 'btn btn-success'))}}

    {{Form::close()}}

    <br>

    <h3>Permisos</h3>

    <div class="list-group">
        @foreach($permisos as $permiso)
        <span>{{ $permiso->name }}</span><br>
        @endforeach
    </div>

</section>

@stop