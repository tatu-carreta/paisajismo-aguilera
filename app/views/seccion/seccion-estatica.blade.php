

    @foreach($seccion -> items as $i)
    @if(!is_null($i->html()))
    <div class="col-md-12">
    @else
    <div class="col-md-6">
    @endif
        @if(Auth::check())
        <div class="row pull-right">
            <div class="col-md-12">
            @if(!is_null($i->texto()))
                @if(Auth::user()->can("editar_texto"))
                        <a href="{{URL::to($prefijo.'/admin/texto/editar/'.$i->id)}}" class="btn popup-nueva-seccion"><i class="fa fa-pencil fa-lg"></i>editar</a>
                @endif
                @if(Auth::user()->can("borrar_texto"))
                        <a onclick="borrarData('{{URL::to('admin/seccion/borrar')}}', '{{$seccion->id}}');" class="btn"><i class="fa fa-times fa-lg"></i>eliminar</a>
                @endif
            @elseif(!is_null($i->html()))
                @if(Auth::user()->can("editar_html"))
                        <a href="{{URL::to($prefijo.'/admin/html/editar/'.$i->id)}}" class="btn popup-nueva-seccion"><i class="fa fa-pencil fa-lg"></i>editar</a>
                @endif
                @if(Auth::user()->can("borrar_html"))
                        <a onclick="borrarData('{{URL::to('admin/seccion/borrar')}}', '{{$seccion->id}}');" class="btn"><i class="fa fa-times fa-lg"></i>eliminar</a>
                @endif
            @elseif(!is_null($i->galeria()))
                @if(Auth::user()->can("editar_galeria"))
                        <a href="{{URL::to($prefijo.'/admin/galeria/editar/'.$i->id)}}" class="btn popup-nueva-seccion"><i class="fa fa-pencil fa-lg "></i>editar</a>
                @endif
                @if(Auth::user()->can("borrar_galeria"))
                        <a onclick="borrarData('{{URL::to('admin/seccion/borrar')}}', '{{$seccion->id}}');" class="btn"><i class="fa fa-times fa-lg"></i>eliminar</a>
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
    </div>
    @endforeach

    <!-- INICIO DE SLIDE -->

    @foreach($seccion -> slides as $s)
    <div class="col-md-6">
        @if(Auth::check())
        <div class="row pull-right">
            <div class="col-md-12">
            @if(Auth::user()->can("editar_slide"))
                <a class="btn" href="{{URL::to('admin/slide/editar/'.$s->id.'/seccion/E')}}"> <i class="fa fa-pencil fa-lg"></i>editar</a>
            @endif
            @if(Auth::user()->can("borrar_slide"))
                <a onclick="borrarData('{{URL::to('admin/seccion/borrar')}}', '{{$seccion->id}}');" class="btn"><i class="fa fa-times fa-lg"></i>eliminar</a>
            @endif
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="slideInternos">
                    <div class="cycle-slideshow slideInternoItem">
                        @foreach($s->imagenes as $img)
                            <img alt="{{$s->nombre}}" src="{{ URL::to($img->carpeta.$img->nombre) }}">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach