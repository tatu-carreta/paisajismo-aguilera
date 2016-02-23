@extends('item.editar-general')

@section('funcs')

<script>
    $(function(){
        $(".selectMarca").change(function () {
            var id = $(".selectMarca option:selected").val();
            if (id != "")
            {
                $.post("{{URL::to('admin/marca/imagen')}}", {'marca_id': id}, function (data) {
                    $(".marca_imagen_preview").html(data);
                });
            }
            else
            {
                $(".marca_imagen_preview").html("");
            }
        });
    });
    
</script>
<script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
<script src="{{URL::to('js/producto-funcs.js')}}"></script>

@stop

@section('destacado-contenido')
<div class="radio divEstadoNuevo">
    <label>
        <input id="" class="" type="checkbox" name="item_destacado" value="N" @if($item->producto()->nuevo()) checked="true" @endif>
        <i class="fa fa-tag prodDestacado fa-lg"></i>
        Nuevo
    </label>
</div>

<div class="divEstadoOferta">
    <div class="checkEstado">
        <div class="radio">
            <label>
                <input id="" class=" precioDisabled" type="checkbox" name="item_destacado" value="O" @if($item->producto()->oferta()) checked="true" @endif>
                <i  class="fa fa-usd prodDestacado fa-lg"></i>
                Oferta
            </label>
        </div>
    </div>
    <div class="divPrecio">
        <label for="" >
            <span>Precio después $ </span><input id="" class="form-control inputWidth60 precioAble1 precio-number" type="text" name="precio_actual" value="{{ $producto->precio(2) }}">
        </label>
    </div>
    <div class="divPrecio">
        <label for="" >
            <span>Precio antes $ </span><input id="" class="form-control inputWidth60 precioAble1 precio-number" type="text" name="precio_antes" value="{{ $producto->precio(1) }}">
        </label>
    </div>   
    <div class="clearfix"></div>
</div>
<!-- <p class="infoTxt"><i class="fa fa-info-circle"></i>Los productos NUEVOS y las OFERTAS se muestran también en la home.</p> -->
@stop

@section('bloque-2')
<!-- Marca -->
<div class="col-md-6 divDatos divCargaMarca">
    <h3>Marca</h3>
    <div class="form-group fondoDestacado">
        <div class="row">
            <div class="col-md-9">
                <select class="form-control selectMarca" name="marca_principal" id="marca_principal">
                    <option value="">Seleccione una Marca</option>
                    @foreach($marcas_principales as $marca)
                        <option value="{{$marca->id}}" @if(!is_null($producto->marca_principal())) @if($marca->id == $producto->marca_principal()->id) selected @endif @endif>{{$marca->nombre}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="marca_imagen_preview"></div>
            </div>
        </div>


        <!-- <input class="form-control" type="text" name="titulo" placeholder="Código" required="true" maxlength="9"> -->
        <p class="infoTxt"><i class="fa fa-info-circle"></i>...</p>
    </div>
</div>
@stop

@section('bloque-3')
<!-- Precio -->
<div class="col-md-6 divDatos divCargaPrecio">
    <h3>Precio</h3>
    <div class="form-group fondoDestacado">
        <input id="" class="form-control inputWidth60 precio-number" type="text" name="precio" value="{{ $producto->precio(2) }}">
        <!-- <input class="form-control" type="text" name="titulo" placeholder="Código" required="true" maxlength="9"> -->
        <p class="infoTxt"><i class="fa fa-info-circle"></i>...</p>
    </div>
</div>
@stop

@section('cuerpo')
<!-- Texto Descriptivo del Producto u obra -->
<div class="divCargaTxtDesc">
    <h3>Detalles técnicos</h3>
    <div class="divEditorTxt fondoDestacado">
        <textarea id="texto" contenteditable="true" name="cuerpo">{{ $item->producto()->cuerpo }}</textarea>
    </div>
</div>
@stop

@section('ubicacion')

    @include('ubicacion.modulo-ubicacion-editar')
    
@stop

@section('hidden')
    {{Form::hidden('continue', $continue)}}
    {{Form::hidden('id', $item->id)}}
    {{Form::hidden('producto_id', $producto->id)}}
    {{Form::hidden('descripcion', '')}}
    {{Form::hidden('tipo_precio_id[]', '2')}}
    @if($seccion_next != 'null')
        {{Form::hidden('seccion_id', $seccion_next)}}
    @endif
@stop