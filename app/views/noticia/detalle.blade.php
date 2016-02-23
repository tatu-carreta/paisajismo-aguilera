@extends($project_name.'-master')

@section('contenido')
<style>
    .fecha {margin-top: 0; color: #286090 }
    .bajada {color: #286090}
</style>

<section class="container">
    <div class="row">
        <div class="col-md-12 marginBottom2">
            <h2>{{ $item -> titulo }}</h2>
            <a class="volveraSeccion" href="{{URL::to('/'.$item -> seccionItem() -> menuSeccion() -> url)}}"><i class="fa fa-caret-left"></i>{{ Lang::get('html.volver_a') }} {{ $item -> seccionItem() -> menuSeccion() -> nombre }}</a>
            @if(Auth::check())
                @if(Auth::user()->can("editar_item"))
                <a href="{{URL::to('admin/noticia/editar/'.$item->texto()->noticia()->id)}}" data='{{$item -> seccionItem() -> id}}' style="display:none">Editar<i class="fa fa-pencil fa-lg"></i></a>
                @endif
            @endif
        </div>
    </div>
    <div class="clear"></div>
    
    <div class="row marginBottom2 ">
        <div class="col-md-12">
            <div class="editor">
                <p class="fecha">{{ date('d/m/Y', strtotime($item->texto()->noticia()->fecha)) }}</p>
                <p>Fuente: <strong>{{ $item->texto()->noticia()->fuente }}</strong></p>
                <p class="bajada">{{ $item->descripcion }}</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-4">
            <div class="thumbnail">
                @if(count($item->imagen_destacada()) > 0)
                    @if(!Auth::check())
                        <a class="fancybox" href="{{URL::to($item->imagen_destacada()->ampliada()->carpeta.$item->imagen_destacada()->ampliada()->nombre)}}" title="{{ $item->imagen_destacada()->ampliada()->epigrafe }}" rel='group'>
                    @endif
                        <img src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}" alt="{{$item->titulo}}">
                    @if(!Auth::check())
                        </a>
                    @endif
                    {{-- <p>{{$item->imagen_destacada()->epigrafe}}</p> --}}
                @else
                    @if(!Auth::check())
                        <a class="fancybox" href="{{ URL::to('images/sinImg.gif') }}" title="{{$item->titulo}}" rel='group'>
                    @endif
                        <img src="{{ URL::to('images/sinImg.gif') }}" alt="{{$item->titulo}}">
                    @if(!Auth::check())
                        </a>
                    @endif
                @endif
            </div>
        </div>
        
        @foreach($item->imagenes as $img)
            <div class="col-md-3 col-sm-4 col-xs-4">
                <div class="thumbnail ">
                    @if(!Auth::check())
                        <a class="fancybox" href="{{URL::to($img->ampliada()->carpeta.$img->ampliada()->nombre)}}" title="{{ $img->ampliada()->epigrafe }}" rel='group'>
                    @endif
                            <img src="{{ URL::to($img->carpeta.$img->nombre) }}" alt="{{$item->titulo}}">
                    @if(!Auth::check())    
                        </a>
                    @endif
                    {{-- <p>{{$img->epigrafe}}</p> --}}
                </div>
            </div>
            
        @endforeach
    </div>
    
    <div class="clear"></div>
    <div class="row ">
        <div class="col-md-12 divCuerpoTxt">
            {{ $item->texto()->cuerpo }}
        </div>
    </div>
</section>
@stop