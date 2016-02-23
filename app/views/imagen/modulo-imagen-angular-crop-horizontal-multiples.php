<script>
    $(document).ready(function () {
        $(".imagen").change(function () {
            var id = $(this).attr('data');
            $(".url-archivo" + id).val($(this).val());
        });
    });
</script>


<div class="row marginBottom1">
    <div class="col-md-6 divInputFile">
        <label class="btn btn-primary"> Seleccionar archivo
            <span>
                <input id="fileInput" type="file" nv-file-select="" uploader="uploader" name="imagen_portada_original" class='oculto file imagen' data="1"/>
            </span>
        </label>
        <input type="text" class="url-archivo1 form-control">
    </div>
    <div class="col-md-6"></div>
</div>

<div class="row marginBottom1">
    <div class="col-md-6">
        <p>Modifique la selección para definir el recorte:</p>
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
    
    <div class="col-md-6">
        <p>Vista previa de la imagen chica:</p>
        <div class="divVistaImgCargada">
            <img ng-src="<% croppedImage %>" />
        </div>
        <input class="form-control marginBottom1" id="epigrafe" type="text" name="epigrafe_imagen_portada" placeholder="Ingrese una descripción de la foto (opcional)">
        <div class="nombre-peso marginBottom2">
            <div>
                <p>Progreso:</p>
                <div class="progress" style="">
                    <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
                </div>
            </div>
            <button type="button" class="btn btn-primary btn-s" ng-click="uploader.uploadAll()" ng-disabled="!uploader.getNotUploadedItems().length">
                    Guardar recorte
            </button>
            <button type="button" class="btn btn-default btn-s" ng-click="removerImagen()" ng-disabled="!uploader.queue.length">
                Eliminar
            </button>
        </div>
    </div>
    <div class="clearfix"></div>
</div> <!-- Cierra row -->
    
