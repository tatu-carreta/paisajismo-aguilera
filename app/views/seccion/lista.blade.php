@extends($project_name.'-master')

@section('contenido')
<section class="container">
    <div class="row marketing">
        <h3>Crear Sección</h3>

        {{ Form::open(array('url' => 'admin/seccion/agregar')) }}
        @if(Session::get('mensaje'))
        <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
        @endif
        <div class="form-group">
            {{Form::label('titulo', 'Título')}}
            {{Form::text('titulo', Input::old('titulo'), array('class' => 'form-control', 'placeholder' => 'Título de la Sección', 'autocomplete' => 'off'))}}
        </div>

        @if($errors -> has('titulo'))
        <div class="alert alert-danger">
            @foreach($errors->get('titulo') as $error)
            *{{ $error }}<br>
            @endforeach
        </div>
        @endif

        <div class="form-group">
            {{Form::label('menu_id', 'Menú')}}
            <select class="form-control" name="menu_id">
                <option value="">Seleccione un Menú</option>
                @foreach($menus as $m)
                <option value="{{$m->id}}">{{$m->nombre}}</option>
                @endforeach
            </select>
        </div>

        @if($errors -> has('menu_id'))
        <div class="alert alert-danger">
            @foreach($errors->get('menu_id') as $error)
            *{{ $error }}<br>
            @endforeach
        </div>
        @endif

        {{Form::button('Cancelar', array('class' => 'volver btn btn-default'))}}
        {{Form::submit('Guardar', array('class' => 'btn btn-success'))}}

        {{Form::close()}}

        <br>

        <h3>Secciones Contenido</h3>

        <div class="list-group">
            @foreach($secciones as $s)
                @if(count($s->items) > 0)
                    <a href="{{URL::to('seccion/'.$s->id)}}" class="list-group-item col-lg-6">
                        @if($s->titulo != "")
                        {{$s->titulo}}
                        @else
                        Sin título {{$s->id}}
                        @endif
                        >>
                        @foreach($s->menu as $m)
                        {{$m->nombre}}
                        @endforeach
                    </a>
                    <a href="{{URL::to('admin/seccion/editar/'.$s->id)}}" class="col-lg-3 btn btn-lg btn-primary">Editar Sección</a>
                    <a href="#" onclick="borrarData('seccion/borrar', '{{$s->id}}');" class="col-lg-3 btn btn-lg btn-danger">Borrar Sección</a>
                @endif
            @endforeach
        </div>
        <br>
        <h3>Secciones SIN Contenido</h3>

        <div class="list-group">
            @foreach($secciones as $s)
                @if(count($s->items) == 0)
                    <a href="{{URL::to('seccion/'.$s->id)}}" class="list-group-item col-lg-6">
                        @if($s->titulo != "")
                        {{$s->titulo}}
                        @else
                        Sin título {{$s->id}}
                        @endif
                        >>
                        @foreach($s->menu as $m)
                        {{$m->nombre}}
                        @endforeach
                    </a>
                    <a href="{{URL::to('admin/seccion/editar/'.$s->id)}}" class="col-lg-3 btn btn-lg btn-primary">Editar Sección</a>
                    <a href="#" onclick="borrarData('seccion/borrar', '{{$s->id}}');" class="col-lg-3 btn btn-lg btn-danger">Borrar Sección</a>
                @endif
            @endforeach
        </div>

    </div>
</section>


@stop