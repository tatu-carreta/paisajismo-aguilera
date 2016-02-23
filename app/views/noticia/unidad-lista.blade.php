<style>
    .divInfoNoticia{
        margin-left: 230px;
    }
    .fecha {margin-top: 0; color: #286090 }
    .bajada {color: #286090}
</style>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 moduloItem">
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
                    <a href="{{URL::to('noticia/'.$i->url)}}"><i class="fa fa-eye fa-lg"></i></a>
                </span>
                <span class="pull-right">
                    @if(Auth::user()->can("editar_item"))
                        <a href="{{URL::to('admin/noticia/editar/'.$i->texto()->noticia()->id.'/seccion/'.$seccion->id)}}" data='{{$seccion->id}}'><i class="fa fa-pencil fa-lg"></i></a>
                    @endif
                    @if(Auth::user()->can("borrar_item"))
                        <a onclick="borrarData('{{URL::to('admin/item/borrar')}}', '{{$i->id}}');"><i class="fa fa-times fa-lg"></i></a>
                    @endif
                </span>
                <div class="clearfix"></div>
            </div>
        @endif

        @if(!Auth::check())
            <a href="{{URL::to('noticia/'.$i->url)}}">
        @endif
            <div class="bandaInfoProd">
                <div class="divImgItem pull-left" style="width: 100px;">
                    <img class="lazy" data-original="@if(!is_null($i->imagen_destacada())){{ URL::to($i->imagen_destacada()->carpeta.$i->imagen_destacada()->nombre) }}@else{{URL::to('images/sinImg.gif')}}@endif" alt="{{$i->titulo}}">
                </div>
            
                <div class="divInfoNoticia">
                    <p class="fecha">{{ date('d/m/Y', strtotime($i->texto()->noticia()->fecha)) }}</p>
                    <h2>{{$i->titulo}}</h2>
                    <p class="bajada">{{$i->descripcion}}</p>
                </div>
            </div>
        @if(!Auth::check())
            </a>
        @endif

        @if(Auth::check())
            <input type="hidden" name="orden[]" value="{{$i->id}}">
        @endif            		
    </div>
</div>
