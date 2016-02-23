@extends($project_name.'-master')

@section('title') Administrar Menú @stop

@section('contenido')
    <script src="{{URL::to('js/popupFuncs.js')}}"></script>
    <section class="container admin">
        @if (Session::has('mensaje'))
            <div class="divAlerta ok alert-success">{{ Session::get('mensaje') }}<i onclick="" class="cerrarDivAlerta fa fa-times fa-lg"></i></div>
        @endif
        <h2>Administrador del Menú</h2>
        <div class="marginBottom2">
            @if(Auth::user()->can("agregar_menu_boton"))
                <a data="{{URL::to('admin/menu/agregar')}}" class="btn marginRight5 editarContenidoAdmin">Agregar Botón</a>
            @endif
            @if(Auth::user()->can("agregar_menu_categoria"))
                <a data="{{URL::to('admin/categoria/agregar')}}" class="btn editarContenidoAdmin">Agregar Categoría</a>
            @endif
        </div>

        <div class="cargaContenidoAdmin bgGris pull-left col50">
            @if(Auth::user()->can("agregar_menu_categoria"))
                @include('categoria.agregar-boton-dinamico')
            @endif
        </div>

        <div class="col-md-6 pull-right">
            <h3>Menú Categorías</h3>

            <div class="grupoMenuAdmin">
                @if(Auth::user()->can("ordenar_menu_principal"))
                    @if(count($menus) >= 2)
                        <a style="padding-left:0 !important" href="{{URL::to('admin/menu/ordenar-menu')}}" class="btnSec menuOrdenar1 popupOrdenMenu"><i class="fa fa-exchange fa-lg"></i>Ordenar Botones</a>
                    @endif
                @endif

                <ul class="listaMenu">
                @foreach($menus as $m)
                    <li>
                        <span><a href="{{URL::to('/'.$m->url)}}">{{$m->lang()->nombre}}</a></span>
                        @if(Auth::user()->can("editar_menu_principal"))
                            <a data="@if(!is_null($m->categoria())) {{URL::to($prefijo.'/admin/categoria/editar/'.$m->categoria()->id)}} @else {{URL::to($prefijo.'/admin/menu/editar/'.$m->id)}} @endif" class="btnSec editarContenidoAdmin"><i class="fa fa-pencil fa-lg"></i></a>
                        @endif
                        @if(Auth::user()->can("borrar_menu_principal"))
                            <a href="#" onclick="@if(!is_null($m->categoria())) borrarData('categoria/borrar', '{{$m->categoria()->id}}'); @else borrarData('menu/borrar', '{{$m->id}}'); @endif" class="btnSec"><i class="fa fa-times fa-lg"></i></a>
                        @endif
                        @if(Auth::user()->can("ordenar_menu_interno"))
                            @if(count($m->children) >= 2)
                                <a href="{{URL::to('admin/menu/ordenar-submenu/'.$m->id)}}" class="btnSec menuOrdenar popupOrdenMenu"><i class="fa fa-exchange fa-lg"></i>Ordenar</a>
                            @endif
                        @endif
                        <ul>
                            @foreach($m->children as $menu2)
                                <li>
                                    <span><a href="{{URL::to('/'.$menu2->lang()->url)}}">{{$menu2->lang()->nombre}}</a></span>
                                    @if(Auth::user()->can("editar_menu_interno"))
                                        <a data="@if(!is_null($menu2->categoria())) {{URL::to($prefijo.'/admin/categoria/editar/'.$menu2->categoria()->id)}} @else {{URL::to($prefijo.'/admin/menu/editar/'.$menu2->id)}} @endif" class="btnSec editarContenidoAdmin"><i class="fa fa-pencil fa-lg"></i></a>
                                    @endif
                                    @if(Auth::user()->can("borrar_menu_interno"))
                                        <a href="#" onclick="@if(!is_null($menu2->categoria())) borrarData('categoria/borrar', '{{$menu2->categoria()->id}}'); @else borrarData('menu/borrar', '{{$menu2->id}}'); @endif" class="btnSec"><i class="fa fa-times fa-lg"></i></a>
                                    @endif
                                    @if(Auth::user()->can("ordenar_menu_interno"))
                                        @if(count($menu2->children) >= 2)
                                            <a href="{{URL::to('admin/menu/ordenar-submenu/'.$menu2->id)}}" class="btnSec menuOrdenar popupOrdenMenu"><i class="fa fa-exchange fa-lg"></i>Ordenar</a>
                                        @endif
                                    @endif
                                    <ul>
                                    @foreach($menu2->children as $menu3)
                                        <li>
                                            <span><a href="{{URL::to('/'.$menu3->lang()->url)}}">{{$menu3->lang()->nombre}}</a></span>
                                            @if(Auth::user()->can("editar_menu_interno"))
                                                <a data="@if(!is_null($menu3->categoria())) {{URL::to('admin/categoria/editar/'.$menu3->categoria()->id)}} @else {{URL::to('admin/menu/editar/'.$menu3->id)}} @endif" class="btnSec editarContenidoAdmin"><i class="fa fa-pencil fa-lg"></i></a>
                                            @endif
                                            @if(Auth::user()->can("borrar_menu_interno"))
                                                <a href="#" onclick="@if(!is_null($menu3->categoria())) borrarData('categoria/borrar', '{{$menu3->categoria()->id}}'); @else borrarData('menu/borrar', '{{$menu3->id}}'); @endif" class="btnSec "><i class="fa fa-times fa-lg"></i></a>
                                            @endif
                                            @if(Auth::user()->can("ordenar_menu_interno"))
                                                @if(count($menu3->children) >= 2)
                                                    <a href="{{URL::to('admin/menu/ordenar-submenu/'.$menu3->id)}}" class="btnSec menuOrdenar popupOrdenMenu"><i class="fa fa-exchange fa-lg"></i>Ordenar</a>
                                                @endif
                                            @endif
                                            <ul>
                                                @foreach($menu3->children as $menu4)
                                                    <li>
                                                    <span><a href="{{URL::to('/'.$menu4->url)}}">{{$menu4->nombre}}</a></span>
                                                    @if(Auth::user()->can("editar_menu_interno"))
                                                        <a data="@if(!is_null($menu4->categoria())) {{URL::to('admin/categoria/editar/'.$menu4->categoria()->id)}} @else {{URL::to('admin/menu/editar/'.$menu4->id)}} @endif" class="btnSec editarContenidoAdmin"><i class="fa fa-pencil fa-lg"></i></a>
                                                    @endif
                                                    @if(Auth::user()->can("borrar_menu_interno"))
                                                        <a href="#" onclick="@if(!is_null($menu4->categoria())) borrarData('categoria/borrar', '{{$menu4->categoria()->id}}'); @else borrarData('menu/borrar', '{{$menu4->id}}'); @endif" class="btnSec"><i class="fa fa-times fa-lg"></i></a>
                                                    @endif
                                                    @if(Auth::user()->can("ordenar_menu_interno"))
                                                        @if(count($menu4->children) >= 2)
                                                            <a href="{{URL::to('admin/menu/ordenar-submenu/'.$menu4->id)}}" class="btnSec menuOrdenar popupOrdenMenu"><i class="fa fa-exchange fa-lg"></i>Ordenar</a>
                                                        @endif
                                                    @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
                </ul>
            </div>
            @if(Auth::check())
            <div id="orden"></div>
            @endif
        </div>
        <div class="clear"></div>
    </section>

@stop