<h3>Agregar Categoría</h3>

{{ Form::open(array('url' => 'admin/categoria/agregar')) }}
    @if(Session::get('mensaje'))
        <div class="alertOk">{{ Session::get('mensaje') }}</div>
    @endif
    <div class="grupoForm marginBottom2">
        <p class="consigna"><label for="nombre">1. Defina el nombre de la Categoría</label></p>
        <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre de la Categoría" required="true">
    </div>

    @if($errors -> has('nombre'))
        <div class="alertError">
            @foreach($errors->get('nombre') as $error)
                *{{ $error }}<br>
            @endforeach
        </div>
    @endif

    @if(Auth::user()->can("elegir_modulo"))
        <div class="grupoForm marginBottom2">
            <p class="consigna"><label for="">2. Defina el tipo de contenido que tendrá la página nueva</label></p>
            <select class="form-control" name="modulo_id" required="true">
                <option value="">Seleccione un módulo</option>
                @foreach($modulos as $modulo)
                <option value="{{$modulo->id}}">{{$modulo->nombre}}</option>
                @endforeach
            </select>
        </div>
    @else
        {{Form::hidden('modulo_id', '1')}}
    @endif
    
    <div class="grupoForm marginBottom2">
        <p class="consigna">3. Seleccione a qué nivel pertenece</p>
        <p><input type="radio" name="categoria_id" value="" checked="true" id="principal"><label for="principal">Es categoría principal</label></p>
        @if(Auth::user()->can("seleccionar_nivel_categoria"))
            @foreach($categorias as $categoria1)
            <p><input type="radio" name="categoria_id" value="{{$categoria1->id}}" id="categoria_id{{$categoria1->id}}"><label for="categoria_id{{$categoria1->id}}">Es subcategoría de {{$categoria1->lang()->nombre}}</label></p>
                @foreach($categoria1->children as $categoria2)
                <p><input style="margin-left: 15px;" type="radio" name="categoria_id" value="{{$categoria2->id}}" id="categoria_id{{$categoria2->id}}"><label for="categoria_id{{$categoria2->id}}">Es subcategoría de {{$categoria1->lang()->nombre}} / {{$categoria2->lang()->nombre}}</label></p>
                    @foreach($categoria2->children as $categoria3)
                    <p><input style="margin-left: 30px;" type="radio" name="categoria_id" value="{{$categoria3->id}}" id="categoria_id{{$categoria3->id}}"><label for="categoria_id{{$categoria3->id}}">Es subcategoría de {{$categoria1->lang()->nombre}} / {{$categoria2->lang()->nombre}} / {{$categoria3->lang()->nombre}}</label></p>
                        @foreach($categoria3->children as $categoria4)
                        <p><input style="margin-left: 45px;" type="radio" name="categoria_id" value="{{$categoria4->id}}" id="categoria_id{{$categoria4->id}}"><label for="categoria_id{{$categoria4->id}}">Es subcategoría de {{$categoria1->lang()->nombre}} / {{$categoria2->lang()->nombre}} / {{$categoria3->lang()->nombre}} / {{$categoria4->lang()->nombre}}</label></p>
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </div>

    @if($errors -> has('categoria_id'))
        <div class="alert alert-danger">
            @foreach($errors->get('categoria_id') as $error)
                *{{ $error }}<br>
            @endforeach
        </div>
    @endif

    <div class="pull-right">
        {{Form::submit('Guardar', array('class' => 'btn btn-primary'))}}
    </div>

{{Form::close()}}