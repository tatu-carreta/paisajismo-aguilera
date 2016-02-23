@extends('casi-master')

@section('contenido')
<script type="text/javascript" charset="utf-8">
    $(window).load(function() {
        $('.slideProducto').flexslider({
            controlNav: false, //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
            directionNav: true
        });
    });
</script>
<section>
    <div class="container">
        <h2><span class="">{{ $item -> titulo }}</span> / <span class="marcaTit">Marca: @if(!is_null($item->producto()->marca_principal())){{ $item->producto()->marca_principal()->nombre }}@endif</span></h2>
        @if($item->destacado())
        <p class="precioTit">Precio: ${{ $item->producto()->precio(2) }}</p>
        @else
        <p class="precioTit naranja">Precio: consulte</p>
        @endif
        
        <!--columna producto y descripcion -->
        <div class="col70 borderTop">
        @if(count($item->imagenes_producto()) > 0)
            <div class="imgProd">
                <div class="slideProducto">
                    <h3>Imágenes</h3>
                    <ul class="slides">
                        @foreach($item->imagenes_producto() as $img)
                        <li><img src="{{ URL::to($img->carpeta.$img->nombre) }}" alt="$item->titulo"></li>
                        @endforeach
                    </ul>
                    @if(count($item->producto()->marcas_secundarias()) > 0)
                        @foreach($item->producto()->marcas_secundarias() as $marca)
                            <img class="marcaSecundaria" src="{{URL::to($marca->imagen()->carpeta.$marca->imagen()->nombre)}}" alt="{{$marca->nombre}}">
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
            
            <div class="detalleProd">
                @if(!is_null($item->producto()->marca_principal()))
                    <img class="marcaPrincipal" src="{{URL::to($item->producto()->marca_principal()->imagen()->carpeta.$item->producto()->marca_principal()->imagen()->nombre)}}" alt="{{$item->producto()->marca_principal()->nombre}}">
                @endif
                <div class="editor">
                    {{ $item->producto()->cuerpo }}
                </div>
                
                @if(count($item->archivos) > 0)
                    <h3 class="marginBottom05">Descargas PDF</h3>

                    @foreach($item->archivos as $archivo)
                    <a href="{{URL::to($archivo->carpeta.$archivo->nombre)}}" class="descargarPDF">{{$archivo->titulo}} ({{$archivo->nombre}})</a>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="clear"></div>
    </div>
</section>
@stop