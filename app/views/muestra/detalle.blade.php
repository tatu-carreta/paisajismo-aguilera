@extends($project_name.'-master')

@section('contenido')
    @if(Auth::check())
        @if(Auth::user()->can("ordenar_item"))
            <script>
                $(function() {
                    $('.sortable').sortable({
                        update: function(event, ui) {
                            $("#formularioOrdenImagenes").submit();
                        }
                    });
                });
            </script>
        @endif
    @endif
<section class="container">
    <div class="row">
        <div class="col-md-12 marginBottom2">
            <h2>{{ $item ->lang() -> titulo }}</h2>
            <a class="volveraSeccion" href="{{URL::to($prefijo.'/'.$item -> seccionItem() -> menuSeccion() ->lang() -> url)}}"><i class="fa fa-caret-left"></i>{{ Lang::get('html.volver_a') }} {{ $item -> seccionItem() -> menuSeccion()->lang() -> nombre }}</a>
            @if(Auth::check())
                @if(Auth::user()->can("editar_item"))
                <a href="{{URL::to('admin/muestra/editar/'.$item->muestra()->id)}}" data='{{$item -> seccionItem() -> id}}' style="display:none">Editar<i class="fa fa-pencil fa-lg"></i></a>
                @endif
            @endif
        </div>
    </div>
    <div class="clear"></div>
    <div class="row marginBottom2 ">
        
        @if(Auth::check())
            {{ Form::open(array('url' => 'admin/imagen/ordenar-por-item', 'id' => 'formularioOrdenImagenes')) }}
        @endif
        <div class="@if(Auth::check()) sortable @endif">
        <div class="col-md-3 col-sm-4 col-xs-4 @if(Auth::check()) cursor-move @endif">
            <div class="thumbnail">
                @if(count($item->imagen_destacada()) > 0)
                    @if(!Auth::check())
                        <a class="fancybox" href="{{URL::to($item->imagen_destacada()->ampliada()->carpeta.$item->imagen_destacada()->ampliada()->nombre)}}" title="{{ $item->imagen_destacada()->ampliada()->lang()->epigrafe }}" rel='group'>
                    @endif
                        <img src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}" alt="{{$item->lang()->titulo}}">
                    @if(!Auth::check())
                        </a>
                    @endif
                    {{-- <p>{{$item->imagen_destacada()->epigrafe}}</p> --}}
                @else
                    @if(!Auth::check())
                        <a class="fancybox" href="{{ URL::to('images/sinImg.gif') }}" title="{{$item->lang()->titulo}}" rel='group'>
                    @endif
                        <img src="{{ URL::to('images/sinImg.gif') }}" alt="{{$item->lang()->titulo}}">
                    @if(!Auth::check())
                        </a>
                    @endif
                @endif
                @if(Auth::check())
                    <input type="hidden" name="orden[]" value="{{$item->imagen_destacada()->id}}">
                @endif   
            </div>
        </div>
        
        @foreach($item->imagenes as $img)
            <div class="col-md-3 col-sm-4 col-xs-4 @if(Auth::check()) cursor-move @endif">
                <div class="thumbnail ">
                    @if(!Auth::check())
                        <a class="fancybox" href="{{URL::to($img->ampliada()->carpeta.$img->ampliada()->nombre)}}" title="{{ $img->ampliada()->lang()->epigrafe }}" rel='group'>
        @endif
                            <img src="{{ URL::to($img->carpeta.$img->nombre) }}" alt="{{$item->lang()->titulo}}">
                    @if(!Auth::check())    
                        </a>
                    @endif
                    {{-- <p>{{$img->epigrafe}}</p> --}}
                    @if(Auth::check())
                        <input type="hidden" name="orden[]" value="{{$img->id}}">
                    @endif    
                </div>
            </div>
            
        @endforeach
    </div>
        
        @if(Auth::check())
            {{Form::hidden('item_id', $item->id)}}
            {{Form::close()}}
        @endif
    </div>
    <div class="clear"></div>
    <div class="row ">
        <div class="col-md-12 divCuerpoTxt">
            {{ $item->muestra()->lang()->cuerpo }}
        </div>
    </div>
    
    @if(count($item->videos) > 0)
        {{--<div class="row ">
            <div class="col-md-12">
                <h3>Videos</h3>
            </div>
        </div>
        --}}
        <div class="row">
            @foreach($item->videos as $video)
                <div class="col-md-4">
                    <iframe class="video-tc" src="@if($video->tipo == 'youtube')https://www.youtube.com/embed/@else//player.vimeo.com/video/@endif{{ $video->url }}"></iframe>
                </div>
            @endforeach
        </div>
    @endif
</section>
@stop
