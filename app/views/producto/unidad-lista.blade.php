
<div class="col-md-3 col-sm-4 col-xs-6 moduloItem">
    <div class="thumbnail">
    @if(Auth::check())
        <div class="iconos">
            <span class="pull-left">
                @if(!$i->producto()->nuevo())
                    @if(Auth::user()->can("destacar_item"))
                        <a class=" @if($i->producto()->oferta()) disabled @endif" @if(!$i->producto()->oferta()) onclick="destacarItemSeccion('{{URL::to('admin/producto/nuevo')}}', '{{$seccion->id}}', '{{$i->id}}');" @endif><i class="fa fa-tag fa-lg"></i>{{ Lang::get('html.nuevo') }}</a>
                    @endif
                @else
                    @if(Auth::user()->can("quitar_destacado_item"))
                        <a onclick="destacarItemSeccion('{{URL::to('admin/item/quitar-destacado')}}', '{{$seccion->id}}', '{{$i->id}}');" ><i class="fa fa-tag prodDestacado fa-lg"></i>{{ Lang::get('html.nuevo') }}</a>
                    @endif
                @endif
                @if(!$i->producto()->oferta())
                    @if(Auth::user()->can("destacar_item"))
                        <a href="{{URL::to('admin/producto/oferta/'.$i->producto()->id.'/'.$seccion->id.'/seccion')}}" class="popup-nueva-seccion"><i  class="fa fa-usd fa-lg"></i>{{ Lang::get('html.oferta') }}</a>
                    @endif
                @else
                    @if(Auth::user()->can("quitar_destacado_item"))
                        <a onclick="destacarItemSeccion('{{URL::to('admin/item/quitar-destacado')}}', '{{$seccion->id}}', '{{$i->id}}');"><i  class="fa fa-usd prodDestacado fa-lg"></i>{{ Lang::get('html.oferta') }}</a>
                    @endif
                @endif
                <a href="{{URL::to($prefijo.'/producto/'.$i->lang()->url)}}"><i class="fa fa-eye fa-lg"></i></a>
                </span>
                <span class="pull-right editarEliminar">
                    @if(Auth::user()->can("editar_item"))
                        <a href="{{URL::to($prefijo.'/admin/'.$seccion->menuSeccion()->modulo()->nombre.'/editar/'.$i->producto()->id.'/seccion/'.$seccion->id)}}" data='{{$seccion->id}}'><i class="fa fa-pencil fa-lg"></i></a>
                    @endif
                    @if(Auth::user()->can("borrar_item"))
                        <a onclick="borrarData('{{URL::to('admin/item/borrar')}}', '{{$i->id}}');"><i class="fa fa-times fa-lg"></i></a>
                    @endif
                </span>
                <div class="clearfix"></div>
        </div>
    @endif

    <a class="fancybox" href="@if(!is_null($i->imagen_destacada())){{URL::to($i->imagen_destacada()->ampliada()->carpeta.$i->imagen_destacada()->ampliada()->nombre)}}@else{{URL::to('images/sinImg.gif')}}@endif" title="{{ $i->lang()->titulo }} @if(!is_null($i->imagen_destacada())){{ $i->imagen_destacada()->ampliada()->lang()->epigrafe }}@else{{$i->lang()->titulo}}@endif" rel='group'>
        <div class="divImgItem">
            <img class="lazy" data-original="@if(!is_null($i->imagen_destacada())){{ URL::to($i->imagen_destacada()->carpeta.$i->imagen_destacada()->nombre) }}@else{{URL::to('images/sinImg.gif')}}@endif" alt="{{$i->lang()->titulo}}">
            @if($i->producto()->oferta())
                <span class="bandaOfertas">{{ Str::upper(Lang::get('html.oferta')) }}: ${{$i->producto()->precio(1)}} <span>({{ Str::lower(Lang::get('html.oferta_antes')) }}: ${{$i->producto()->precio(2)}})</span></span>
            @elseif($i->producto()->nuevo())
                <span class="bandaNuevos">{{ Str::upper(Lang::get('html.nuevo')) }}</span>
            @endif
        </div>
    </a>

    <div class="bandaInfoProd @if($i->producto()->nuevo()) nuevos @elseif($i->producto()->oferta()) ofertas @endif" id="Pr{{$i->producto()->id}}">
        <p class="pull-left">{{ $i->lang()->titulo }}</p>
        {{-- <p class="marca">Marca: @if(!is_null($i->producto()->marca_principal())){{$i->producto()->marca_principal()->nombre}}@endif</p> --}}
        @if(!Auth::check())
            @if($c = Cart::search(array('id' => $i->producto()->id)))
                <a class="carrito boton-presupuestar btn pull-right" href="{{URL::to('carrito/borrar/'.$i->producto()->id.'/'.$c[0].'/seccion/'.$seccion->id)}}"><i class="fa fa-check-square-o"></i>{{ Lang::get('html.presupuestar') }}</a>
            @else
                <a href="{{URL::to('carrito/agregar/'.$i->producto()->id.'/seccion/'.$seccion->id)}}" class="boton-presupuestar btn pull-right"><i class="fa fa-square-o"></i>{{ Lang::get('html.presupuestar') }}</a>
                {{-- <a class="carrito btn btn-default pull-right" href="{{URL::to('carrito/agregar/'.$i->producto()->id)}}">Agregar Carrito</a> --}}
            @endif
        @endif
        <div class="clearfix"></div>
    </div>

    {{--
    @if($i->producto()->oferta())
        <span class="precio">Oferta: ${{$i->producto()->precio(2)}} ${{$i->producto()->precio(1)}}</span>
    @elseif($i->producto()->nuevo())
        <span>NUEVO</span>
    @endif
    --}}
    {{-- <a class="detalle" href="{{URL::to('producto/'.$i->url)}}">Detalle</a>	--}}
    @if(Auth::check())
        <input type="hidden" name="orden[]" value="{{$i->id}}">
    @endif            		
    </div>
</div>
