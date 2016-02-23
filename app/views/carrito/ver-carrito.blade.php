@extends($project_name.'-master')

@section('contenido')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12 marginBottom2">
                    <h2 class="pull-left">{{ Lang::get('html.presupuesto') }}</h2>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                <p class="infoCarrito marginBottom2"><i class="fa fa-shopping-cart"></i>{{ Lang::get('html.carrito.texto_carrito') }}</p>
                    <div class="col-tabla">
                        <h3>{{ Lang::get('html.carrito.productos_seleccionados') }}</h3>
                        <table class="table">
                            <tbody>
                                @if(Cart::count()>0)
                                    @foreach(Cart::content() as $producto)
                                        <tr>
                                            <td> <img class="lazy" data-original="@if(!is_null($producto->producto->item()->imagen_destacada())){{ URL::to($producto->producto->item()->imagen_destacada()->carpeta.$producto->producto->item()->imagen_destacada()->nombre) }}@else{{URL::to('images/sinImg.gif')}}@endif" alt="{{$producto->producto->item()->lang()->titulo}}"></td>
                                            <td> COD: {{ $producto->producto->item()->lang()->titulo }}</td>
                                            <td> <input class="cant_prod_carrito form-control valid-number" type="text" name="cantidad" value="{{ $producto->qty }}" data='{{ $producto->rowid }}' id="{{ $producto->id }}"></td>
                                            <td> <a href="{{URL::to('carrito/borrar/'.$producto->id.'/'.$producto->rowid.'/carrito/c')}}"><i class="fa fa-times fa-lg"></i></a></td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td>{{ Lang::get('html.carrito.no_productos') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-carrito">
                        <h3>{{ Lang::get('html.carrito.complete_datos') }}</h3>
                        <div class="formulario">
                            {{ Form::open(array('url' => 'pedido/agregar', 'class' => 'borde', 'role' => 'form')) }}
                                <div class="form-group">
                                    <label for="nombre">{{ Lang::get('html.contacto.apeynom') }}</label>
                                    {{Form::text('nombre', Input::old('nombre'),  array('id' => 'nombre','class' => 'form-control', 'required' => true))}}    
                                    <!--<input type="type" class="form-control" id="ejemplo_email_1"
                                           placeholder="" name="nombre">-->
                                </div>
                                <div class="form-group">
                                    <label for="empresa">{{ Lang::get('html.contacto.empresa') }}</label>
                                    {{Form::text('empresa', Input::old('empresa'),  array('id' => 'empresa','class' => 'form-control'))}} 
                                    <!--<input type="type" class="form-control" id="ejemplo_password_1" 
                                           placeholder="" name="empresa">-->
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
            </div>
        </div>          
    </section>
@stop