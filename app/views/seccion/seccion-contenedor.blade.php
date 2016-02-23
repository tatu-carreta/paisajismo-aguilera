@foreach($menu_basic -> secciones as $seccion)
    @if((count($seccion->items) > 0) || (Auth::check()))
        <div class="row @if(Auth::check()) divListadoItems @endif">
                <div  class="col-md-12" id="{{$menu_basic->estado.$menu->menu_id}}">
                    @if(($seccion->lang()->titulo != "") || (Auth::check()))
                        <h3 class="pull-left" id="{{$seccion->estado.$seccion->id}}">
                            @if($seccion->lang()->titulo != "")
                                {{ $seccion->lang()->titulo }}
                            {{-- @else 
                                @if(Auth::check()) 
                                    Sección sin título {{ $seccion->id }}
                                @endif  --}}
                            @endif 
                        </h3>
                        
                    @endif
                    
                    @if(Auth::check())
                        @if(Auth::user()->can("editar_seccion"))
                            <a href="{{URL::to($prefijo.'/admin/seccion/editar/'.$seccion->id)}}" data='{{ $seccion->id }}' class="btn popup-seccion iconoBtn-texto"><i class="fa fa-pencil fa-lg"></i>{{ Lang::get('html.contenedor.cambiar_nombre') }}</a>
                        @endif
                        @if(Auth::user()->can("borrar_seccion"))
                            <a onclick="borrarData('{{URL::to('admin/seccion/borrar')}}', '{{$seccion->id}}');" class="btn iconoBtn-texto"><i class="fa fa-times fa-lg"></i>{{ Lang::get('html.contenedor.eliminar_seccion') }}</a>
                        @endif
                        @if(Auth::user()->can("agregar_item"))
                            <a href="{{URL::to('admin/'.$menu_basic->modulo()->nombre.'/agregar/'.$seccion->id)}}" data='{{ $seccion->id }}' class="btn btn-primary pull-right "><i class="fa fa-plus fa-lg"></i>{{$texto_agregar}}</a>
                        @endif
                    @endif
                    <div class="clearfix"></div>
                    
                </div>
            
        </div>
    @endif
        @if(count($seccion->items) > 0)
            @if(Auth::check())
                {{ Form::open(array('url' => 'admin/item/ordenar-por-seccion', 'id' => 'formularioOrdenSeccion')) }}
            @endif

            <!-- LISTADO -->
            <div class="row @if(Auth::check()) sortable @endif">
                @foreach($seccion -> items as $i)
                    <!-- Item individual -->
                    @include($html)
                @endforeach
            </div>

            @if(Auth::check())
                {{Form::hidden('seccion_id', $seccion->id)}}
                {{Form::close()}}
            @endif
            {{-- {{$seccion->items_noticias()['paginador']->links()}} --}}

        @else
            @if(Auth::check())
            <div class="row">
                <div class="col-md-3">
                    <div class="sinContenido">{{ Lang::get('html.contenedor.sin_contenido', ['texto_modulo' => $texto_modulo]) }}</div>
                </div>
            </div>
            
            @endif
        @endif

    @if(Auth::check())
        <div id="agregar-item{{ $seccion->id }}"></div>
        <div id="destacar-producto"></div>
        <div class="modal fade" id="seccion{{$seccion->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </div>
    @endif
@endforeach