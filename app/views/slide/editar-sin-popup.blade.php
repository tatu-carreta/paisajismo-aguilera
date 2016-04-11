@extends($project_name.'-master')

@section('contenido')
<section class="container"  id="ng-app" ng-app="app">
<h2 class="marginBottom2"><span>Editar slide @if($tipo == 'I')de home @endif</span></h2>
    {{ Form::open(array('url' => 'admin/slide/editar')) }}

        <div class="row marginBottom2">
          <!-- Abre columna de imágenes -->
                <div class="col-md-8 cargaImg">
                    <h4>Cargar imagen nueva</h4>
                    <div class="fondoDestacado">
                        <input type="hidden" ng-model="total_permitido" ng-init="total_permitido = {{ $total_permitido - count($slide->imagenes) }}">
                        @include('imagen.modulo-galeria-angular')
                    </div>

                    @if(count($slide->imagenes) > 0)
                    <h4>Imágenes ya cargadas:</h4>
                    <div class="fondoDestacado">
                        
                            <div class="row imgSeleccionadas">
                              <div class="col-md-12">
                                  @foreach($slide->imagenes as $img)
                                     <div class="fondoBco marginBottom2">
                                         <div class="">
                                           <div class="divCargaImgSlideHome">
                                               <input type="hidden" name="imagen_slide_editar[]" value="{{$img->id}}">
                                               <img src="{{ URL::to($img->carpeta.$img->nombre) }}" alt="{{$slide->titulo}}">
                                                <i onclick="borrarImagenReload('{{ URL::to('admin/imagen/borrar') }}', '{{$img->id}}');" class="fa fa-times-circle fa-lg"></i>
                                            </div>
                                             <?php
                                             /*
                                            <div class="divCargaTxtSlideHome">
                                              <textarea class="form-control" name="epigrafe_imagen_slide_editar[]" maxlength="150">{{$img->epigrafe}}</textarea>
                                            </div>
                                              * 
                                              */
                                             ?>
                                            <div class="clearfix"></div>
                                         </div>
                                     </div>
                                  @endforeach
                                </div>
                            </div>
                        
                    </div>
                    @endif
                </div>
        </div>

        <div class="border-top">
            <button type="submit" class="btn btn-primary marginRight5">Publicar</button>
            <button type="button" class="btn btn-default" onclick="window.history.back();">Cancelar</button>
        </div>  
        {{Form::hidden('slide_id', $slide->id)}}
        {{Form::hidden('continue', $continue)}}
    {{Form::close()}}
</section>
@stop

@section('footer')

    @parent

    <script src="{{URL::to('js/angular-1.3.0.min.js')}}"></script>
    <script src="{{URL::to('js/angular-file-upload.js')}}"></script>
    <script src="{{URL::to('js/ng-img-crop.js')}}"></script>
    <script src="{{URL::to('js/controllers.js')}}"></script>
    <script src="{{URL::to('js/directives-galeria.js')}}"></script>
    
    <script src="{{URL::to('ckeditor/ckeditor.js')}}"></script>
        <script src="{{URL::to('ckeditor/adapters/jquery.js')}}"></script>

@stop