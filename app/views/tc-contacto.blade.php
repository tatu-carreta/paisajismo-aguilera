@extends($project_name.'-master')

@section('contenido')
<section class="container cuerpoColor">
    <a href="{{URL::to('lo-que-nos-proponemos')}}" class="takano"><span>Takano</span></a>
    <div class="row">
        <div class="col-md-12 marginBottom2">
            <h2>{{ Lang::get('html.contacto.titulo_contacto') }}</h2>
            <p>Si desea realizar cualquier consulta puede comunicarse con nosotros por teléfono, por e-mail o completando el siguiente formulario. Le responderemos a la brevedad.</p>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row ">
        <div class="col-md-6">
            <div class="col-contacto">
                <!-- <h3>{{ Lang::get('html.contacto.consultas') }}</h3> -->
                <div class="formulario">
                    {{Form::open(array('url' => 'consulta', 'class' => 'borde'))}}
                        <div class="form-group">
                            <label for="nombre">{{ Lang::get('html.contacto.apeynom') }}</label>
                            {{Form::text('nombre', Input::old('nombre'),  array('id' => 'nombre','class' => 'form-control', 'required' => true))}}    
                            <!--<input type="type" class="form-control" id="ejemplo_email_1"
                                   placeholder="" name="nombre">-->
                        </div>
                        <div class="form-group">
                            <label for="email">{{ Lang::get('html.contacto.email') }}</label>
                            {{Form::email('email', Input::old('email'),  array('id' => 'email','class' => 'form-control', 'required' => true))}} 
                            <!--<input type="email" class="form-control" id="ejemplo_password_1" 
                                   placeholder="" name="email">-->
                        </div>
                        <div class="form-group">
                            <label for="telefono">{{ Lang::get('html.contacto.telefono') }}</label>
                            {{Form::text('telefono', Input::old('telefono'),  array('id' => 'telefono','class' => 'form-control', 'required' => true))}} 
                            <!--<input type="type" class="form-control" id="ejemplo_password_1" 
                                   placeholder="" name="telefono">-->
                        </div>
                        <div class="form-group">
                            <label for="consulta">{{ Lang::get('html.contacto.comentarios') }}</label>
                            {{Form::textarea('consulta', Input::old('consulta'),  array('id' => 'consulta','class' => 'form-control', 'rows' => 3))}} 
                            <!--<textarea class="form-control" rows="3" name="consulta"></textarea>-->
                        </div>
                        <button type="submit" class="btn">{{ Lang::get('html.contacto.boton_enviar') }}</button>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
        <div class="col-md-6"></div>
    </div>
</section>
@stop