@extends($project_name.'-master')

@section('contenido')
    <section class="container">
        {{ Form::open(array('url' => 'admin/portfolio_simple/agregar', 'files' => true, 'role' => 'form')) }}
            <h2><span>Nueva obra</span></h2>
            <div class="marginBottom2">
                <a class="volveraSeccion" href="{{URL::to('/'.Seccion::find($seccion_id) -> menuSeccion() -> url)}}"><i class="fa fa-caret-left"></i>Volver a {{ Seccion::find($seccion_id) -> menuSeccion() -> nombre }}</a>
            </div>
        
            <div class="row">
                <!-- Abre columna Imagen del Portfolio -->
                <div class="col-md-12 divDatos divCargaImg">
                    <h3>Imagen de la obra</h3>
                	<div class="fondoDestacado">
	                    <h4>Nueva imagen</h4>
                        <p class="infoTxt"><i class="fa fa-info-circle"></i>La imagen original no debe exceder los 500kb de peso.</p>
	                    @include('imagen.modulo-imagen-angular-crop-horizontal')
	                </div>
                </div>
            </div>  
            

            <div class="border-top">
                <input type="submit" value="Publicar" class="btn btn-primary marginRight5">
                <a href="{{URL::to('/'.Seccion::find($seccion_id) -> menuSeccion() -> url)}}" class="btn btn-default">Cancelar</a>
            </div>


            {{Form::hidden('seccion_id', $seccion_id)}}
            {{Form::hidden('titulo', '')}}
            {{Form::hidden('descripcion', '')}}
        {{Form::close()}}
    </section>
@stop

@section('footer')

    @parent

    <script src="{{URL::to('js/angular-1.3.0.min.js')}}"></script>
    <script src="{{URL::to('js/angular-file-upload.js')}}"></script>
    <script src="{{URL::to('js/ng-img-crop.js')}}"></script>
    <script src="{{URL::to('js/controllers.js')}}"></script>

@stop