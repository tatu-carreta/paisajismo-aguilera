
<div class="col-md-6">
    @foreach($seccion -> items as $i)
        @if(Auth::check())
        <div class="row pull-right">
            <div class="col-md-12">
            @if(!is_null($i->texto()))
                @if(Auth::user()->can("editar_texto"))
                        <a href="{{URL::to($prefijo.'/admin/texto/editar/'.$i->id)}}" class="btn popup-nueva-seccion iconoBtn-texto"><i class="fa fa-pencil fa-lg"></i>editar</a>
                @endif
                @if(Auth::user()->can("borrar_texto"))
                        <a onclick="borrarData('{{URL::to('admin/seccion/borrar')}}', '{{$seccion->id}}');" class="btn sinPadding iconoBtn-texto"><i class="fa fa-times fa-lg"></i>eliminar</a>
                @endif
            @elseif(!is_null($i->html()))
                @if(Auth::user()->can("editar_html"))
                        <a href="{{URL::to($prefijo.'/admin/html/editar/'.$i->id)}}" class="btn popup-nueva-seccion iconoBtn-texto"><i class="fa fa-pencil fa-lg"></i>editar</a>
                @endif
                @if(Auth::user()->can("borrar_html"))
                        <a onclick="borrarData('{{URL::to('admin/seccion/borrar')}}', '{{$seccion->id}}');" class="btn sinPadding iconoBtn-texto"><i class="fa fa-times fa-lg"></i>eliminar</a>
                @endif
            @elseif(!is_null($i->galeria()))
                @if(Auth::user()->can("editar_galeria"))
                        <a href="{{URL::to($prefijo.'/admin/galeria/editar/'.$i->id)}}" class="btn popup-nueva-seccion iconoBtn-texto"><i class="fa fa-pencil fa-lg "></i>editar</a>
                @endif
                @if(Auth::user()->can("borrar_galeria"))
                        <a onclick="borrarData('{{URL::to('admin/seccion/borrar')}}', '{{$seccion->id}}');" class="btn sinPadding iconoBtn-texto"><i class="fa fa-times fa-lg"></i>eliminar</a>
                @endif
            @endif
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-12 divCuerpoTxt">
            @if(!is_null($i->lang()->titulo) && ($i->lang()->titulo != ""))
                <h2>{{$i->lang()->titulo}}</h2>
            @endif
            @if(is_null($i->texto()) && (is_null($i->html())))
                <p>{{$i->lang()->descripcion}}</p>
            @else
                @if(!is_null($i->texto()))
                    <p>{{$i->texto()->lang()->cuerpo}}</p>
                @else
                    {{$i->html()->lang()->cuerpo}}
                @endif
            @endif
            </div>
            <!-- Galeria de Imagenes -->
            @if(count($i->imagenes) > 0)
            <div class="col-md-12">
                <div class="galeria">
                    @foreach($i->imagenes as $img)
                        <a class="fancybox" rel="group{{$i->id}}" href="{{ URL::to($img->ampliada()->carpeta.$img->ampliada()->nombre) }}" title="{{ $img->ampliada()->lang()->epigrafe }}" target="_blank"><img class="lazy" data-original="{{ URL::to($img->carpeta.$img->nombre) }}"></a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    @endforeach

    <!-- INICIO DE SLIDE -->

    @foreach($seccion -> slides as $s)
        @if(Auth::check())
        <div class="row pull-right">
            <div class="col-md-12">
            @if(Auth::user()->can("editar_slide"))
                <a class="btniconoBtn-texto" href="{{URL::to('admin/slide/editar/'.$s->id.'/seccion')}}"> <i class="fa fa-pencil fa-lg"></i>editar</a>
            @endif
            @if(Auth::user()->can("borrar_slide"))
                <a onclick="borrarData('{{URL::to('admin/seccion/borrar')}}', '{{$seccion->id}}');" class="btn sinPadding iconoBtn-texto"><i class="fa fa-times fa-lg"></i>eliminar</a>
            @endif
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="flexslider">
                    <ul class="slides">
                        @foreach($s->imagenes as $img)
                            <li>
                                <img alt="{{$s->nombre}}" src="{{ URL::to($img->carpeta.$img->nombre) }}">
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
</div>