@extends($project_name.'-master')

@section('contenido')
    <section>
        {{ Form::open(array('url' => 'login', 'class' => '')) }}
            <div class="divLogin form-group">
                <input type="text" id="nombre"  class="form-control" placeholder="nombre de usuario" name="nombre" autofocus><br>
                <input type="password" id="clave"  class="form-control" placeholder="contraseña" name="clave"><br>
                <input type="submit" value="Ingresar" class="btn  btn-default pull-right">
            </div>
        {{Form::hidden('url', $url)}}
        {{Form::close()}}
    </section>
@stop

@section('tarjetas')@stop