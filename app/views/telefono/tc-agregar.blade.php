@extends($project_name.'-master')

@section('contenido')
    <section class="container">
        {{ Form::open(array('url' => 'admin/telefono/agregar')) }}
            <h2 class="marginBottom2"><span>Carga y modificación de telefono</span></h2>

            <!-- Abre columna de descripción -->
            <div class="col70Admin datosProducto">
                <h3>Caracteristica</h3>
                <input class="block anchoTotal marginBottom" type="text" name="caracteristica" placeholder="Car" required="true" maxlength="100">

                <h3>Numero</h3>
                <input class="block anchoTotal marginBottom" type="text" name="telefono" placeholder="Numero" required="true" maxlength="100">

                <h3>Tipo Telefono</h3>
                <select name="tipo_telefono_id">
                    <option value="1">Móvil</option>
                    <option value="2">Fijo</option>
                    <option value="3">Fax</option>
                </select>
            </div>

            <div class="punteado">
                <input type="submit" value="Publicar" class="btn marginRight5">
                <a onclick="window.history.back();" class="btnGris">Cancelar</a>
            </div>
        {{Form::close()}}
    </section>
@stop
