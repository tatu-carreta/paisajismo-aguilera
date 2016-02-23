@extends($project_name.'-master')

@section('contenido')
    <section class="container">
        {{ Form::open(array('url' => 'admin/marca/editar', 'files' => true, 'role' => 'form')) }}
            <h2><span>Carga y modificación de marcas</span></h2>

            <div class="row">
                <div class="col-md-6 divDatos">
                    <h3>Nombre de la marca</h3>
                    <div class="form-group fondoDestacado">
                        <input class="form-control" type="text" name="nombre" placeholder="Nombre de la marca" required="true" maxlength="50" value="{{$marca->nombre}}">
                        <p class="infoTxt"><i class="fa fa-info-circle"></i>No puede haber dos productos con igual código. Máximo 50 caracteres.</p>
                    </div>
                </div>

                <div class="col-md-6 divDatos">
                    <h3>Tipo de Marca</h3>
                    <div class="form-group fondoDestacado">
                        <select name="tipo" class="form-control" required="true">
                            <option value="">Seleccione un Tipo de Marca</option>
                            <option value="P" @if($marca->tipo == "P") selected @endif>Marca de Producto</option>
                            <option value="S" @if($marca->tipo == "S") selected @endif>Marca Técnica</option>
                        </select>
                    </div>
                </div>
           </div>

           <div class="row">
                <h3>Imagen principal</h3>
            @if(!is_null($marca->imagen()))
                <div class="divCargaImgProducto" style="margin-bottom:0 !important">
                    <div class="marginBottom2 divCargaImg">
                        <img alt="{{$marca->nombre}}"  src="{{ URL::to($marca->imagen()->carpeta.$marca->imagen()->nombre) }}">
                        <i onclick="borrarImagenReload('{{URL::to('admin/marca/quitar-imagen')}}', '{{$marca->id}}');" class="fa fa-times-circle fa-lg"></i>
                    </div>
                    {{Form::hidden('imagen_id', $marca->imagen()->id)}}
                </div>
            @else
                @include('imagen.modulo-imagen-angular-crop-horizontal')
                
                @section('footer')

                    @parent

                    <script src="{{URL::to('js/angular-1.3.0.min.js')}}"></script>
                    <script src="{{URL::to('js/angular-file-upload.js')}}"></script>
                    <script src="{{URL::to('js/ng-img-crop.js')}}"></script>
                    <script src="{{URL::to('js/controllers.js')}}"></script>

                @stop
            @endif
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="border-top">                
                        <input type="submit" value="Publicar" class="btn btn-primary marginRight5">
                        <a href="{{URL::to('/admin/marca')}}" class="btn btn-default">Cancelar</a>
                    </div>
                </div>
            </div>
            {{Form::hidden('id', $marca->id)}}
        {{Form::close()}}
    </section>
@stop
