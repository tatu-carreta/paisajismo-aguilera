@extends($project_name.'-master')

@section('contenido')
    <script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
    
    <section class="container">
        @if (Session::has('mensaje'))
            <div class="divAlerta error alert-success">{{ Session::get('mensaje') }}<i onclick="" class="cerrarDivAlerta fa fa-times fa-lg"></i></div>
        @endif
        {{ Form::open(array('url' => 'admin/persona/agregar')) }}
            <h2 class="marginBottom2"><span>Carga y modificación de persona</span></h2>
            <div id="error" class="error" style="display:none"><span></span></div>
            <div id="correcto" class="correcto ok" style="display:none"><span></span></div>

            <!-- Abre columna de descripción -->
            <div class="col70Admin datosProducto">
                <h3>Apellido</h3>
                <input class="block anchoTotal marginBottom" type="text" name="apellido" placeholder="Apellido" required="true" maxlength="100">

                <h3>Nombre</h3>
                <input class="block anchoTotal marginBottom fecha" type="text" name="nombre" placeholder="Nombre" required="true" maxlength="50">

                <h3>Email</h3>
                <input class="block anchoTotal marginBottom" type="email" name="email" placeholder="Email" required="true" maxlength="50">

                <h3>Fecha de Nacimiento</h3>
                <input class="block anchoTotal marginBottom" type="date" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" required="true" maxlength="200">

            
                @include('direccion.'.$project_name.'-agregar-sin-form')
            </div>
            <div class="clear"></div>
            <!-- cierran columnas -->
            
            <div class="punteado">
                <input type="submit" value="Publicar" class="btn marginRight5">
                <a onclick="window.history.back();" class="btnGris">Cancelar</a>
            </div>
        {{Form::close()}}
    </section>
@stop
