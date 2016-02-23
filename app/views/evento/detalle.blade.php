@extends($project_name.'-master')

@section('contenido')
<section>
    @if (Session::has('mensaje'))
        <div class="divAlerta ok alert-success">{{ Session::get('mensaje') }}<i onclick="" class="cerrarDivAlerta fa fa-times fa-lg"></i></div>
    @endif
    <div class="container">
        <h2><span class=""><a class="volveraSeccion" href="{{URL::to('/'.$item -> seccionItem() -> menuSeccion() -> url)}}">{{ $item -> seccionItem() -> menuSeccion() -> nombre }}</a></span></h2>
       
        @if(Auth::check())
            @if(Auth::user()->can("editar_item"))
            <a href="{{URL::to('admin/evento/editar/'.$item->texto()->evento()->id)}}" data='{{$item -> seccionItem() -> id}}' style="display:none">Editar<i class="fa fa-pencil fa-lg"></i></a>
            @endif
        @endif
        
        <!--columna producto y descripcion -->
        <div class="col70">
            <div class="imgProd">
                        @if(count($item->imagen_destacada()) > 0)
                                <img src="{{ URL::to($item->imagen_destacada()->carpeta.$item->imagen_destacada()->nombre) }}" alt="{{$item->titulo}}">
                                <p>{{$item->imagen_destacada()->epigrafe}}</p>
                        @else
                            <li><img src="{{ URL::to('images/sinImg.gif') }}" alt="{{$item->titulo}}"></li>
                        @endif
            </div>
            
            <div class="detalleProd">
                <h2>{{ $item -> titulo }}</h2> 
                <div class="editor">
                    <h4>Fecha Desde</h4>
                    {{ date('d/m/Y', strtotime($item->texto()->evento()->fecha_desde)) }}
                </div>
                <div class="editor">
                    <h4>Fecha Hasta</h4>
                    {{ date('d/m/Y', strtotime($item->texto()->evento()->fecha_hasta)) }}
                </div>
                <div class="editor">
                    <h4>Descripcion</h4>
                    {{ $item->descripcion }}
                </div>
                <div class="editor">
                    <h4>Cuerpo</h4>
                    {{ $item->texto()->cuerpo }}
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</section>
@stop