@extends($project_name.'-master')

@section('footer_consulta_form')
<div class="clear"></div>
@stop

@section('contenido')
@if(count($item->imagenes_producto()) > 1)
<script type="text/javascript" charset="utf-8">
    $(window).load(function() {
        $('.slideProducto').flexslider({
            controlNav: false, //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
            directionNav: true
        });
    });
</script>
@endif
<section class="container">
    <div class="row">
        <div class="col-md-12 marginBottom2">
            <h2>{{ $item -> seccionItem() -> lang() -> titulo }}</h2>
            <a class="volveraSeccion" href="{{URL::to($prefijo.'/'.$item -> seccionItem() -> menuSeccion() -> lang() -> url)}}"><i class="fa fa-caret-left"></i>{{ Lang::get('html.volver_a') }} {{ $item -> seccionItem() -> menuSeccion() -> lang() -> nombre }}</a>
        </div>
    </div>
    
    <div class="row marginBottom2">
        <div class="col-md-6">
            @if(!is_null($item->imagen_destacada()))
            <div class="row">
                <div class="col-md-12">
                    <div class="divImgItem">
                        <img class="lazy" src="{{URL::to($item->imagen_destacada()->ampliada()->carpeta.$item->imagen_destacada()->ampliada()->nombre)}}">
                    </div>
                </div>
            </div>
            
            @endif
            @if(count($item->imagenes_producto_editar()) > 0)
            <div class="row">
                @foreach($item->imagenes_producto_editar() as $img)
                <div class="col-md-6">
                    <div class="divImgItem">
                        <img src="{{ URL::to($img->carpeta.$img->nombre) }}" alt="{{$item->lang()->titulo}}">
                        <p>{{$img->lang()->epigrafe}}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-2">
                    <div class="marca_imagen_preview marginBottom1">
                    @if(!is_null($item->producto()->marca_principal()))
                        <img class="marcaPrincipal lazy" src="@if(!is_null($item->producto()->marca_principal()->imagen())){{URL::to($item->producto()->marca_principal()->imagen()->carpeta.$item->producto()->marca_principal()->imagen()->nombre)}}@else{{URL::to('images/sinImg.gif')}}@endif" alt="{{$item->producto()->marca_principal()->nombre}}">
                    @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h2>{{ $item -> lang() -> titulo }}</h2>
                </div>
            </div>
            @if($item->producto()->oferta())
            <div class="row">
                <div class="col-md-12">
                    <span>{{ Str::upper(Lang::get('html.oferta')) }}: ${{$item->producto()->precio(1)}} <span>({{ Str::lower(Lang::get('html.oferta_antes')) }}: ${{$item->producto()->precio(2)}})</span></span>
                </div>
            </div>
            @elseif($item->producto()->nuevo())
            <div class="row">
                <div class="col-md-12">
                    <span>{{ Str::upper(Lang::get('html.nuevo')) }}</span>
                </div>
            </div>
            @elseif($item->producto()->precio(2) != 0)
            <div class="row marginBottom2">
                <div class="col-md-12">
                    <span class="precio-detProd">{{ Str::upper(Lang::get('html.producto.precio')) }}: ${{ $item->producto()->precio(2) }}</span>
                </div>
            </div>
            @endif
            @if($item->producto()->lang()->cuerpo != "")
            <div class="row marginBottom2">
                <div class="col-md-12">
                    <h4>{{ Lang::get('html.producto.descripcion_tecnica') }}</h4>
                    <p>{{ $item->producto()->lang()->cuerpo }}</p>
                </div>
            </div>
            @endif
            @if(count($item->archivos) > 0)
            <div class="row marginBottom2">
                <div class="col-md-12">
                    <h4>{{ Lang::get('html.descargas_pdf') }}</h4>
                </div>
                @foreach($item->archivos as $archivo)
                <div class="col-md-12">
                    <a href="{{URL::to($archivo->carpeta.$archivo->nombre)}}" class="descargarPDF">{{$archivo->titulo}}</a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    
    @if(count($item->videos) > 0)
        <div class="row ">
            <div class="col-md-12">
                <h4>Videos</h4>
                <div class="row "> 
                    @foreach($item->videos as $video)
                        <div class="col-md-4">
                            <iframe class="video-tc" src="@if($video->tipo == 'youtube')https://www.youtube.com/embed/@else//player.vimeo.com/video/@endif{{ $video->url }}"></iframe>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</section>
@stop