<h3>Agregar botón</h3>

{{ Form::open(array('url' => 'admin/menu/agregar')) }}
    @if(Session::get('mensaje'))
        <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
    @endif
    <div class="grupoForm marginBottom2">
        <p class="consigna"><label for="nombre">1. Defina el nombre del botón</label></p>
        <input type="text" name="nombre" placeholder="Nombre del Botón" required="true" id="nombre">
    </div>

    @if($errors -> has('nombre'))
    <div class="alert alert-danger">
        @foreach($errors->get('nombre') as $error)
        *{{ $error }}<br>
        @endforeach
    </div>
    @endif

    <div class="grupoForm marginBottom2">
        <p class="consigna"><label for="">2. Defina la función que tendrá la página nueva</label></p>
        <select class="form-control" name="tipo_pagina" required="true">
            <option value="">Seleccione un Tipo de Página</option>
            @if(Auth::user()->can("agregar_pagina_inicio"))
                <option value="1">Es página de Inicio</option>
            @endif
            @if(Auth::user()->can("agregar_pagina_contacto"))
                <option value="2">Es página de Contacto</option>
            @endif
            @if(Auth::user()->can("agregar_pagina_carrito"))
                <option value="3">Es página de Carrito</option>
            @endif
            <option value="4">Es página de Contenido</option>
        </select>
    </div>

    @if($errors -> has('tipo_pagina'))
    <div class="alert alert-danger">
        @foreach($errors->get('tipo_pagina') as $error)
        *{{ $error }}<br>
        @endforeach
    </div>
    @endif

    <div class="grupoForm marginBottom2">
        <p class="consigna">3. Seleccione a qué nivel pertenece</p>
        <p><input type="radio" name="menu_id" value="" checked="true" id="principal"><label for="principal">Es botón principal</label></p>
        @if(Auth::user()->can("seleccionar_nivel_menu"))
            @foreach($menus as $menu1)
                @if(is_null($menu1->categoria()))
                <p><input type="radio" name="menu_id" value="{{$menu1->id}}" id="menu_id{{$menu1->id}}"><label for="menu_id{{$menu1->id}}">Es botón secundario de {{$menu1->nombre}}</label></p>
                @foreach($menu1->children as $menu2)
                    <p><input style="margin-left: 15px;" type="radio" name="menu_id" value="{{$menu2->id}}" id="menu_id{{$menu2->id}}"><label for="menu_id{{$menu2->id}}">Es botón secundario de {{$menu1->nombre}} / {{$menu2->nombre}}</label></p>
                    @foreach($menu2->children as $menu3)
                        <p><input style="margin-left: 30px;" type="radio" name="menu_id" value="{{$menu3->id}}" id="menu_id{{$menu3->id}}"><label for="menu_id{{$menu3->id}}">Es botón secundario de {{$menu1->nombre}} / {{$menu2->nombre}} / {{$menu3->nombre}}</label></p>
                        @foreach($menu3->children as $menu4)
                        <p><input style="margin-left: 45px;" type="radio" name="menu_id" value="{{$menu4->id}}" id="menu_id{{$menu4->id}}"><label for="menu_id{{$menu4->id}}">Es botón secundario de {{$menu1->nombre}} / {{$menu2->nombre}} / {{$menu3->nombre}} / {{$menu4->nombre}}</label></p>
                        @endforeach
                    @endforeach
                @endforeach
                @endif
            @endforeach
        @endif
    </div>

    @if($errors -> has('menu_id'))
    <div class="alert alert-danger">
        @foreach($errors->get('menu_id') as $error)
        *{{ $error }}<br>
        @endforeach
    </div>
    @endif
    <div class="floatRight">
        {{Form::submit('Guardar', array('class' => 'btn'))}}
    </div>


{{Form::close()}}