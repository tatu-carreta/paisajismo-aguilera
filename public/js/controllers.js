'use strict';


angular


        .module('app', ['angularFileUpload', 'ngImgCrop'], function ($interpolateProvider) {
            $interpolateProvider.startSymbol('<%');
            $interpolateProvider.endSymbol('%>');
        })


        .controller('ImagenSingular', ['$scope', 'FileUploader', function ($scope, FileUploader) {
                $scope.size = 'small';
                $scope.type = 'square';
                $scope.image = '';
                $scope.croppedImage = '';
                $scope.resImgFormat = 'image/png';
                $scope.resImgQuality = 1;
                $scope.selMinSize = 100;
                $scope.resImgSize = 280;
                $scope.url = '';
                $scope.original = "";

                $scope.todo_error = false;

                $scope.$watch('url_public', cambiaUrlUploader, true);
                function cambiaUrlUploader() {
                    $scope.uploader.url = $scope.url_public + '/admin/imagen/crop/upload';
                    //console.log($scope.uploader.url);
                }

                $scope.removerImagen = function () {
                    uploader.clearQueue();
                    $scope.image = '';
                    $scope.croppedImage = '';
                    angular.element(document.querySelector('#fileInput')).val('');
                    angular.element(document.querySelector('.url-archivo1')).val('');
                    angular.element(document.querySelector('#epigrafe')).val('');
                };

                $scope.onChange = function ($dataURI) {
                    console.log('onChange fired - ' + $scope.foto._file);
                    var blob = dataURItoBlob($dataURI);
                    $scope.foto._file = blob;
                    console.log('onChange fired - ' + $scope.foto._file.type);
                };
                $scope.onLoadBegin = function () {
                    console.log('onLoadBegin fired');
                };
                $scope.onLoadDone = function () {
                    console.log('onLoadDone fired');
                };
                $scope.onLoadError = function () {
                    console.log('onLoadError fired');
                };
                var handleFileSelect = function (evt) {
                    var file = evt.currentTarget.files[0];
                    var reader = new FileReader();
                    reader.onload = function (evt) {
                        $scope.$apply(function ($scope) {
                            $scope.imageDataURI = evt.target.result;
                        });
                    };
                    reader.readAsDataURL(file);
                };
                angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
                $scope.$watch('resImageDataURI', function () {
                    //console.log('Res image', $scope.resImageDataURI);
                });

                var uploader = $scope.uploader = new FileUploader({
                    url: 'admin/imagen/crop/upload'
                });

                // FILTERS

                uploader.filters.push({
                    name: 'customFilter',
                    fn: function (item /*{File|FileLikeObject}*/, options) {
                        return this.queue.length < 1;
                    },
                    texto: 'Está intentando cargar una imagen nueva',
                });
                uploader.filters.push({
                    name: 'sizeLimit',
                    fn: function (item /*{File|FileLikeObject}*/, options) {
                        //500kb
                        return item.size < 500000;
                    },
                    texto: 'La imagen no debe exceder los 500kb de peso.'
                });

                // CALLBACKS

                uploader.onWhenAddingFileFailed = function (item /*{File|FileLikeObject}*/, filter, options) {
                    console.info('onWhenAddingFileFailed', item, filter, options);
                    alert(filter.texto);
                };
                /*
                 uploader.onAfterAddingFile = function (fileItem) {
                 console.info('onAfterAddingFile', fileItem);
                 };
                 */
                uploader.onAfterAddingFile = function (item) {
                    $scope.original = item._file;
                    $scope.foto = item;
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        $scope.$apply(function () {
                            $scope.image = event.target.result;
                        });
                    };
                    reader.readAsDataURL(item._file);
                };

                /**
                 * Upload Blob (cropped image) instead of file.
                 * @see
                 *   https://developer.mozilla.org/en-US/docs/Web/API/FormData
                 *   https://github.com/nervgh/angular-file-upload/issues/208
                 */
                uploader.onBeforeUploadItem = function (item) {
                    var blob = dataURItoBlob($scope.croppedImage);
                    item._file = blob;

                };

                /**
                 * Converts data uri to Blob. Necessary for uploading.
                 * @see
                 *   http://stackoverflow.com/questions/4998908/convert-data-uri-to-file-then-append-to-formdata
                 * @param  {String} dataURI
                 * @return {Blob}
                 */
                var dataURItoBlob = function (dataURI) {
                    var binary = atob(dataURI.split(',')[1]);
                    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
                    var array = [];
                    for (var i = 0; i < binary.length; i++) {
                        array.push(binary.charCodeAt(i));
                    }
                    return new Blob([new Uint8Array(array)], {type: mimeString});
                };

                uploader.onAfterAddingAll = function (addedFileItems) {
                    console.info('onAfterAddingAll', addedFileItems);
                };
                uploader.onBeforeUploadItem = function (item) {

                    item.formData.push({imagen_ampliada: $scope.original});

                    console.info('onBeforeUploadItem', item, $scope.original);
                };
                uploader.onProgressItem = function (fileItem, progress) {
                    console.info('onProgressItem', fileItem, progress);
                };
                uploader.onProgressAll = function (progress) {
                    console.info('onProgressAll', progress);
                };
                uploader.onSuccessItem = function (fileItem, response, status, headers) {
                    console.info('onSuccessItem', fileItem, response, status, headers);
                };
                uploader.onErrorItem = function (fileItem, response, status, headers) {
                    console.info('onErrorItem', fileItem, response, status, headers);
                };
                uploader.onCancelItem = function (fileItem, response, status, headers) {
                    console.info('onCancelItem', fileItem, response, status, headers);
                };
                uploader.onCompleteItem = function (fileItem, response, status, headers) {
                    console.info('onCompleteItem', response);

                    if (angular.isUndefined(response.imagen_path))
                    {
                        $scope.todo_error = true;
                    }
                    else
                    {
                        $scope.imagen_portada = response.imagen_path;
                        $scope.imagen_ampliada = response.imagen_ampliada;
                    }
                };
                uploader.onCompleteAll = function () {
                    console.info('onCompleteAll');
                    if (!$scope.todo_error)
                    {
                        $scope.todo_ok = true;
                    }
                };

                console.info('uploader', uploader);
            }])
        .controller('ImagenMultiple', ['$scope', 'FileUploader', function ($scope, FileUploader) {
                $scope.size = 'small';
                $scope.type = 'square';
                $scope.image = '';
                $scope.croppedImage = '';
                $scope.resImgFormat = 'image/png';
                $scope.resImgQuality = 1;
                $scope.selMinSize = 100;
                $scope.resImgSize = 280;
                $scope.original = "";

                $scope.imagenes_seleccionadas = [];


                $scope.removerImagen = function () {
                    uploader.clearQueue();
                    $scope.image = '';
                    $scope.croppedImage = '';
                    angular.element(document.querySelector('#fileInput')).val('');
                    angular.element(document.querySelector('.url-archivo1')).val('');
                    angular.element(document.querySelector('#epigrafe')).val('');
                };

                $scope.onChange = function ($dataURI) {
                    console.log('onChange fired - ' + $scope.foto._file);
                    var blob = dataURItoBlob($dataURI);
                    $scope.foto._file = blob;
                    console.log('onChange fired - ' + $scope.foto._file.type);
                };
                $scope.onLoadBegin = function () {
                    console.log('onLoadBegin fired');
                };
                $scope.onLoadDone = function () {
                    console.log('onLoadDone fired');
                };
                $scope.onLoadError = function () {
                    console.log('onLoadError fired');
                };
                var handleFileSelect = function (evt) {
                    var file = evt.currentTarget.files[0];
                    var reader = new FileReader();
                    reader.onload = function (evt) {
                        $scope.$apply(function ($scope) {
                            $scope.imageDataURI = evt.target.result;
                        });
                    };
                    reader.readAsDataURL(file);
                };
                angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
                $scope.$watch('resImageDataURI', function () {
                    //console.log('Res image', $scope.resImageDataURI);
                });
                
                $scope.$watch('url_public', cambiaUrlUploader, true);
                function cambiaUrlUploader() {
                    $scope.uploader.url = $scope.url_public + '/admin/imagen/crop/upload';
                    //console.log($scope.uploader.url);
                }
                var uploader = $scope.uploader = new FileUploader({
                    url: 'admin/imagen/crop/upload'
                });

                // FILTERS

                uploader.filters.push({
                    name: 'customFilter',
                    fn: function (item /*{File|FileLikeObject}*/, options) {
                        return this.queue.length < 1;
                    },
                    texto: 'Está intentando cargar una imagen nueva',
                });
                uploader.filters.push({
                    name: 'sizeLimit',
                    fn: function (item /*{File|FileLikeObject}*/, options) {
                        //500kb
                        return item.size < 500000;
                    },
                    texto: 'Excede los 500kb'
                });

                // CALLBACKS

                uploader.onWhenAddingFileFailed = function (item /*{File|FileLikeObject}*/, filter, options) {
                    console.info('onWhenAddingFileFailed', item, filter, options);
                    alert(filter.texto);
                };
                /*
                 uploader.onAfterAddingFile = function (fileItem) {
                 console.info('onAfterAddingFile', fileItem);
                 };
                 */
                uploader.onAfterAddingFile = function (item) {
                    $scope.original = item._file;
                    $scope.foto = item;
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        $scope.$apply(function () {
                            $scope.image = event.target.result;
                        });
                    };
                    reader.readAsDataURL(item._file);
                };

                /**
                 * Upload Blob (cropped image) instead of file.
                 * @see
                 *   https://developer.mozilla.org/en-US/docs/Web/API/FormData
                 *   https://github.com/nervgh/angular-file-upload/issues/208
                 */
                uploader.onBeforeUploadItem = function (item) {
                    var blob = dataURItoBlob($scope.croppedImage);
                    item._file = blob;

                };

                /**
                 * Converts data uri to Blob. Necessary for uploading.
                 * @see
                 *   http://stackoverflow.com/questions/4998908/convert-data-uri-to-file-then-append-to-formdata
                 * @param  {String} dataURI
                 * @return {Blob}
                 */
                var dataURItoBlob = function (dataURI) {
                    var binary = atob(dataURI.split(',')[1]);
                    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
                    var array = [];
                    for (var i = 0; i < binary.length; i++) {
                        array.push(binary.charCodeAt(i));
                    }
                    return new Blob([new Uint8Array(array)], {type: mimeString});
                };

                uploader.onAfterAddingAll = function (addedFileItems) {
                    console.info('onAfterAddingAll', addedFileItems);
                };
                uploader.onBeforeUploadItem = function (item) {

                    item.formData.push({imagen_ampliada: $scope.original});

                    console.info('onBeforeUploadItem', item, $scope.original);
                };
                uploader.onProgressItem = function (fileItem, progress) {
                    console.info('onProgressItem', fileItem, progress);
                };
                uploader.onProgressAll = function (progress) {
                    console.info('onProgressAll', progress);
                };
                uploader.onSuccessItem = function (fileItem, response, status, headers) {
                    console.info('onSuccessItem', fileItem, response, status, headers);
                };
                uploader.onErrorItem = function (fileItem, response, status, headers) {
                    console.info('onErrorItem', fileItem, response, status, headers);
                };
                uploader.onCancelItem = function (fileItem, response, status, headers) {
                    console.info('onCancelItem', fileItem, response, status, headers);
                };
                uploader.onCompleteItem = function (fileItem, response, status, headers) {
                    console.info('onCompleteItem', response);
                    $scope.imagen_portada = response.imagen_path;
                    $scope.imagen_ampliada = response.imagen_ampliada;

                    var imagen = {
                        'imagen_portada_ampliada': $scope.imagen_ampliada,
                        'src': $scope.croppedImage,
                        'epigrafe': angular.element(document.querySelector('#epigrafe')).val(),
                        'imagen_portada': $scope.imagen_portada
                    };

                    $scope.imagenes_seleccionadas.push(imagen);

                    $scope.removerImagen();
                    console.log($scope.imagenes_seleccionadas);

                };
                uploader.onCompleteAll = function () {
                    console.info('onCompleteAll');

                };

                $scope.borrarImagenCompleto = function (imagen) {
                    if (confirm('¿Está segura que desea eliminar esta imagen?'))
                    {
                        $scope.imagenes_seleccionadas.splice(imagen, 1);
                    }
                };

                console.info('uploader', uploader);
            }])
        .controller('GaleriaUpload', ['$scope', 'FileUploader', function ($scope, FileUploader) {

                $scope.imagenes_seleccionadas = [];
                $scope.nombres_archivos = '';

                $scope.$watch('url_public', cambiaUrlUploader, true);
                function cambiaUrlUploader() {
                    $scope.uploader.url = $scope.url_public + '/admin/imagen/slide/upload';
                    //console.log($scope.uploader.url);
                }

                var uploader = $scope.uploader = new FileUploader({
                    url: 'admin/imagen/slide/upload'
                });

                // FILTERS

                uploader.filters.push({
                    name: 'customFilter',
                    fn: function (item /*{File|FileLikeObject}*/, options) {
                        return this.queue.length < $scope.total_permitido;
                    },
                    texto: 'Está intentando cargar más de las 4 imágenes permitidas.',
                });
                uploader.filters.push({
                    name: 'sizeLimit',
                    fn: function (item /*{File|FileLikeObject}*/, options) {
                        //500kb
                        return item.size < 500000;
                    },
                    texto: 'La imagen no debe exceder los 500kb de peso.'
                });

                uploader.filters.push({
                    name: 'imageFilter',
                    fn: function (item /*{File|FileLikeObject}*/, options) {
                        var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                        return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
                    }
                });
                // CALLBACKS

                uploader.onWhenAddingFileFailed = function (item /*{File|FileLikeObject}*/, filter, options) {
                    console.info('onWhenAddingFileFailed', item, filter, options);
                };
                uploader.onAfterAddingFile = function (fileItem) {
                    cargarNombresArchivos();

                    console.info('onAfterAddingFile', fileItem);
                };
                uploader.onAfterAddingAll = function (addedFileItems) {
                    console.info('onAfterAddingAll', addedFileItems);
                };
                uploader.onBeforeUploadItem = function (item) {
                    console.info('onBeforeUploadItem', item);
                };
                uploader.onProgressItem = function (fileItem, progress) {
                    console.info('onProgressItem', fileItem, progress);
                };
                uploader.onProgressAll = function (progress) {
                    console.info('onProgressAll', progress);
                };
                uploader.onSuccessItem = function (fileItem, response, status, headers) {
                    console.info('onSuccessItem', fileItem, response, status, headers);
                };
                uploader.onErrorItem = function (fileItem, response, status, headers) {
                    console.info('onErrorItem', fileItem, response, status, headers);
                };
                uploader.onCancelItem = function (fileItem, response, status, headers) {
                    console.info('onCancelItem', fileItem, response, status, headers);
                };
                uploader.onCompleteItem = function (fileItem, response, status, headers) {
                    console.info('onCompleteItem', fileItem, response, status, headers);

                    var imagen = {
                        'imagen_slide': response.imagen_path,
                    };

                    $scope.imagenes_seleccionadas.push(imagen);
                };
                uploader.onCompleteAll = function () {
                    console.info('onCompleteAll');
                };

                function cargarNombresArchivos() {
                    $scope.nombres_archivos = '';
                    angular.forEach(uploader.queue, function (item) {
                        $scope.nombres_archivos += item.file.name + ";   ";
                    });
                }


                $scope.removeItem = function (item) {
                    item.remove();
                    cargarNombresArchivos();
                };

                $scope.removeTodos = function (item) {
                    uploader.clearQueue();
                    cargarNombresArchivos();
                };



                console.info('uploader', uploader);
            }]);
