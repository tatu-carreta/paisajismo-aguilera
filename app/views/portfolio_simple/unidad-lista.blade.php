
        <div class="col-md-3 col-sm-4 col-xs-4 moduloItem">
            <div class="thumbnail">
            @if(Auth::check())
                <div class="iconos">
                    <span class="pull-left">
                        @if(!$i->destacado())
                            @if(Auth::user()->can("destacar_item"))
                                <a onclick="destacarItemSeccion('{{URL::to('admin/item/destacar')}}', '{{$seccion->id}}', '{{$i->id}}');"><i class="fa fa-thumb-tack fa-lg"></i></a>
                            @endif
                        @else
                            @if(Auth::user()->can("quitar_destacado_item"))
                                <a onclick="destacarItemSeccion('{{URL::to('admin/item/quitar-destacado')}}', '{{$seccion->id}}', '{{$i->id}}');"><i class="fa fa-thumb-tack prodDestacado fa-lg"></i></a>
                            @endif
                        @endif
                    </span>
                    <span class="pull-right editarEliminar">
                        @if(Auth::user()->can("editar_item"))
                            <a href="{{URL::to('admin/'.$seccion->menuSeccion()->modulo()->nombre.'/editar/'.$i->id.'/seccion/'.$seccion->id)}}" data='{{$seccion->id}}'><i class="fa fa-pencil fa-lg"></i></a>
                        @endif
                        @if(Auth::user()->can("borrar_item"))
                            <a onclick="borrarData('{{URL::to('admin/item/borrar')}}', '{{$i->id}}');"><i class="fa fa-times fa-lg"></i></a>
                        @endif
                    </span>
                    <div class="clearfix"></div>
                </div>
            @endif

            <a class="fancybox" href="@if(!is_null($i->imagen_destacada())){{URL::to($i->imagen_destacada()->ampliada()->carpeta.$i->imagen_destacada()->ampliada()->nombre)}}@else{{URL::to('images/sinImg.gif')}}@endif" title="@if(!is_null($i->imagen_destacada())){{ $i->imagen_destacada()->ampliada()->epigrafe }}@endif" rel='group'>
                <div class="divImgItem">
                    <img class="lazy" data-original="@if(!is_null($i->imagen_destacada())){{ URL::to($i->imagen_destacada()->carpeta.$i->imagen_destacada()->nombre) }}@else{{URL::to('images/sinImg.gif')}}@endif" alt="{{$i->titulo}}">
                </div>
            </a>
            
            @if(Auth::check())
                <input type="hidden" name="orden[]" value="{{$i->id}}">
            @endif            		
            </div>
        </div>
