<script>
    $(document).ready(function () {
        
        $(".imagen").change(function(){
            var id = $(this).attr('data');
            $(".url-archivo"+id).val($(this).val());
        });
        
    });

</script>
<div id="ng-app" ng-app="app">
    <div ng-controller="ImagenSingular" nv-file-drop="" uploader="uploader" filters="queueLimit, customFilter, sizeLimit">

        <div class="row marginBottom1">
            <div class="col-xs-4">
                <label class="btn btn-primary"> Seleccionar archivo
                <span>
                    <input data='1' id="fileInput" type="file" nv-file-select="" uploader="uploader" name="imagen_portada_original" class='oculto file imagen'/>
                </span>
                </label>

            </div>
            <div class="col-xs-8">
                <input type="text" class="url-archivo1 form-control">
                <input type="hidden" name="imagen_portada_crop" value="<% imagen_portada %>">
                <input type="hidden" name="imagen_portada_ampliada" value="<% imagen_ampliada %>">
                <input type="hidden" ng-model="url_public" ng-init="url_public = '{{URL::to('/')}}'">
            </div>
        </div>
        <div class="row marginBottom1">
            <div class="col-xs-12">
                <p class="infoTxt"><i class="fa fa-info-circle">
                </i>La imagen original puede ser vertical u horizontal pero no debe exceder los 500kb de peso.</p>
            </div>
        </div>

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

        <div class="divImgResultante">
            <h4>Resultado del corte:</h4>
            <img ng-src="<% croppedImage %>" />
        </div>

        <input class="marginBottom1 form-control" type="text" name="epigrafe_imagen_portada" placeholder="Ingrese una descripción de la foto (opcional)">

        <div class="row">
            <div class="col-xs-12 progreso">
                <p>Progreso:</p>
                <div class="progress" style="">
                    <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
                </div>
                <span ng-show="todo_ok"><i class="glyphicon glyphicon-ok"></i></span>
                <span ng-show="todo_error"><i class="glyphicon glyphicon-remove"></i></span>
            </div>
        </div>
        
        <div class="row marginBottom1">
            <div class="col-xs-12">
                <button type="button" class="btn btn-primary marginRight5" ng-click="uploader.uploadAll()" ng-disabled="!uploader.getNotUploadedItems().length">Cargar imagen
                </button>
                <button type="button" class="btn btn-default" ng-click="removerImagen()" ng-disabled="!uploader.queue.length">Eliminar imagen
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="infoTxt"><i class="fa fa-info-circle">
                </i>Cuando el progreso haya finalizado publique el producto.</p>
            </div>
        </div>
    </div>
</div>