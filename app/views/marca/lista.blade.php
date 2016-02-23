@extends($project_name.'-master')

@section('contenido')
    <section class="container">
        <div class="row">
            <div class="col-md-12 marginBottom2">
                <h2 class="pull-left">Administración de Marcas</h2>
                 @if(Auth::user()->can("agregar_marca"))
                 <div class="pull-right">
                    <a href="{{URL::to('admin/marca/agregar/')}}" class="btn btn-primary"><i class="fa fa-plus fa-lg"></i>Nueva Marca</a>
                </div>
        @endif
            <div class="clear"></div>
            </div>
        </div>

       

        <div class="row">
            <div class="col-md-12">
                <h3>Marcas de Producto</h3>
            </div>
        </div>
        <div class="row marginBottom2">
            @foreach($marcas_principales as $marca)
                <div class="col-md-2 col-sm-4 col-xs-4 moduloItem">
                    <div class="thumbnail">
                        @if(Auth::check())
                            <div class="iconos">
                                <span class="pull-right editarEliminar">
                                    @if(Auth::user()->can("editar_marca"))
                                        <a href="{{URL::to('admin/marca/editar/'.$marca->id)}}"><i class="fa fa-pencil fa-lg"></i></a>
                                    @endif
                                    @if(Auth::user()->can("borrar_marca"))
                                        <a onclick="borrarData('{{URL::to('admin/marca/borrar')}}', '{{$marca->id}}');"><i class="fa fa-times fa-lg"></i></a>
                                    @endif
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <div class="divImgItem">
                            <img class="lazy" data-original="@if(!is_null($marca->imagen())){{ URL::to($marca->imagen()->carpeta.$marca->imagen()->nombre) }}@else{{URL::to('images/sinImg.gif')}}@endif" alt="{{$marca->nombre}}">
                            <p class="marca"><span>{{$marca->nombre}}</span></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-md-12 marginBottom2">
                <h3>Marcas Técnicas</h3>
            </div>
        </div>
        <div class="row">
            @foreach($marcas_secundarias as $marca)
                <div class="col-md-2 col-sm-4 col-xs-4 moduloItem">
                    <div class="thumbnail">
                        @if(Auth::check())
                            <div class="iconos">
                                <span class="pull-right editarEliminar">
                                    @if(Auth::user()->can("editar_marca"))
                                        <a href="{{URL::to('admin/marca/editar/'.$marca->id)}}"><i class="fa fa-pencil fa-lg"></i></a>
                                    @endif
                                    @if(Auth::user()->can("borrar_marca"))
                                        <a onclick="borrarData('{{URL::to('admin/marca/borrar')}}', '{{$marca->id}}');"><i class="fa fa-times fa-lg"></i></a>
                                    @endif
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        @endif

                        <div class="divImgItem">
                            <img class="lazy" data-original="@if(!is_null($marca->imagen())){{ URL::to($marca->imagen()->carpeta.$marca->imagen()->nombre) }}@else{{URL::to('images/sinImg.gif')}}@endif" alt="{{$marca->nombre}}">
                            <p class="marca"><span>{{$marca->nombre}}</span></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@stop