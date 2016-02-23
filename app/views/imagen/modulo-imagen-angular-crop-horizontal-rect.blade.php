<script>
    $(document).ready(function () {

        $(".imagen").change(function () {
            var id = $(this).attr('data');
            $(".url-archivo" + id).val($(this).val());
        });

    });

</script>


<div id="ng-app" ng-app="app">
    <div ng-controller="ImagenRectangular" nv-file-drop="" uploader="uploader" filters="queueLimit, customFilter, sizeLimit">
        
<input type="file" onchange="angular.element(this).scope().onFile(this.files[0])">
  <button ng-click="preview()">Show preview</button>
  <button ng-click="scale(200)">Scale to 200px width</button>
  <button ng-click="clear()">Clear selection</button>
  <label>Disabled <input type="checkbox" ng-model="options.disabled"></label>

  <br />

  <div ng-if="dataUrl" class="img-container">
    <img ng-if="dataUrl" ng-src="<% dataUrl %>" width="800"
         ng-cropper
         ng-cropper-proxy="cropperProxy"
         ng-cropper-show="showEvent"
         ng-cropper-hide="hideEvent"
         ng-cropper-options="options">
  </div>

  <div class="preview-container">
    <img ng-if="preview.dataUrl" ng-src="<% preview.dataUrl %>">
  </div>
  
  
        <div class="row">
            <div class="col-md-6">
                <div class="row marginBottom1">
                    <div class="col-md-4">
                        <label class="btn btn-primary"> Seleccionar archivo
                            <span>
                                <input id="fileInput" type="file" nv-file-select="" uploader="uploader" name="imagen_portada_original" class='oculto file imagen' data="1"/>
                            </span>
                        </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="url-archivo1 form-control">
                        <input type="hidden" name="imagen_portada_crop" value="<% imagen_portada %>">
                        <input type="hidden" name="imagen_portada_ampliada" value="<% imagen_ampliada %>">
                        <input type="hidden" ng-model="url_public" ng-init="url_public = '{{URL::to('/')}}'">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="cropArea" ng-class="{'big':size == 'big', 'medium':size == 'medium', 'small':size == 'small'}">
                            <img-crop image="image"
                                      result-image="croppedImage"
                                      change-on-fly="changeOnFly"
                                      area-type="<% type %>"
                                      area-min-size="selMinSize"
                                      result-image-format="<% resImgFormat %>"
                                      result-image-quality="resImgQuality"
                                      result-image-size="resImgSize"
                                      on-change="onChange($dataURI)"
                                      on-load-begin="onLoadBegin()"
                                      on-load-done="onLoadDone()"
                                      on-load-error="onLoadError()"
                                      ></img-crop>
                            <!-- crop area if uploaded image
                            <img-crop ng-show="image" image="image" result-image="croppedImage" area-type="square" result-image-size="280"></img-crop>-->
                            <input type="hidden" ng-model="foto">
                            <!--aspect-ratio="aspectRatio"-->
                        </div>
                    </div>
                </div>
            </div><!-- cierra col-md-6 -->

            <div class="col-md-6 resultadoImgCargada">
                <h3>Resultado</h3>
                <div class="divImgResultante marginBottom1">
                    <img ng-src="<% croppedImage %>" />
                </div>

                <input class="form-control marginBottom1" id="epigrafe" type="text" name="epigrafe_imagen_portada" placeholder="Ingrese una descripción de la foto (opcional)">
                    <div class="progreso">
                        <p>Progreso:</p>
                        <div class="progress" style="">
                            <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
                        </div>
                        <span ng-show="todo_ok"><i class="glyphicon glyphicon-ok"></i></span>
                        <span ng-show="todo_error"><i class="glyphicon glyphicon-remove"></i></span>
                    </div>
                    <button type="button" class="btn btn-primary btn-s" ng-click="uploader.uploadAll()" ng-disabled="!uploader.getNotUploadedItems().length">
                        Guardar recorte
                    </button>
                    <button type="button" class="btn btn-default btn-s" ng-click="removerImagen()" ng-disabled="!uploader.queue.length">
                        Eliminar
                    </button>

            </div>
        </div><!-- cierra col-md-6 -->
            <div class="clearfix"></div>
        </div>

    </div>
</div>
<!-- cierra row -->