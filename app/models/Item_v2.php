<?php

class Item_v2 extends Eloquent {

    //Tabla de la BD
    protected $table = 'item';
    //Atributos que van a ser modificables
    protected $fillable = array('titulo', 'descripcion', 'url', 'estado', 'fecha_carga', 'fecha_modificacion', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregarItem($input) {

        $respuesta = array();

        //Se definen las reglas con las que se van a validar los datos..
        $reglas = array(
            'titulo' => array('max:50', 'unique:item'),
            'seccion_id' => array('integer'),
            //'imagen_portada_crop' => array('required'),
        );
        /*
          if (isset($input['titulo']) && ($input['titulo'] != "")) {
          $reglas['titulo'] = array('max:9', 'unique:item');
          }
         * 
         */

        if (isset($input['es_texto']) && ($input['es_texto'])) {
            unset($reglas['imagen_portada_crop']);
        }

        if (isset($input['file']) && ($input['file'] != "") && (!is_array($input['file']))) {
            $reglas['x'] = array('required');
            $reglas['y'] = array('required');
            $reglas['h'] = array('required');
            $reglas['w'] = array('required');
        }

        //Se realiza la validación
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {

            $messages = $validator->messages();
            if ($messages->has('titulo')) {
                $respuesta['mensaje'] = $messages->first('titulo');
            } elseif ($messages->has('imagen_portada_crop')) {
                $respuesta['mensaje'] = 'Se olvidó de guardar la imagen recortada.';
            } else {
                $respuesta['mensaje'] = 'Los datos necesario para el producto son erróneos.';
            }

            //Si está todo mal, carga lo que corresponde en el mensaje.

            $respuesta['error'] = true;
        } else {

            if ($input['titulo'] == "") {
                $max_id = DB::table('item')->max('id');
                $ultimo_id = $max_id + 1;
                $url = 'item-' . $ultimo_id;
            } else {
                $url = $input['titulo'];
            }

            //Se cargan los datos necesarios para la creacion del Item
            $datos = array(
                'titulo' => $input['titulo'],
                'descripcion' => $input['descripcion'],
                'url' => Str::slug($url),
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'fecha_modificacion' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            //Lo crea definitivamente
            $item = static::create($datos);

            if (isset($input['file']) && ($input['file'] != "")) {
                if (is_array($input['file'])) {
                    foreach ($input['file'] as $key => $imagen) {
                        if ($imagen != "") {
                            $imagen_creada = Imagen::agregarImagen($imagen, $input['epigrafe'][$key]);

                            if (!$imagen_creada['error']) {
                                if (isset($input['destacado']) && ($input['destacado'] == $key)) {
                                    $destacado = array(
                                        "destacado" => "A"
                                    );
                                } else {
                                    $destacado = array(
                                        "destacado" => NULL
                                    );
                                }
                                $item->imagenes()->attach($imagen_creada['data']->miniatura()->id, $destacado);
                            }
                        }
                    }
                } else {
                    $coordenadas = array("x" => $input['x'], "y" => $input['y'], "w" => $input['w'], "h" => $input['h']);
                    $imagen_creada = Imagen::agregarImagen($input['file'], $input['epigrafe_imagen_portada'], $coordenadas);

                    $item->imagenes()->attach($imagen_creada['data']->miniatura()->id, array("destacado" => "A"));
                }
            }

            if (isset($input['archivo']) && ($input['archivo'] != "")) {
                if (is_array($input['archivo'])) {
                    foreach ($input['archivo'] as $key => $archivo) {
                        if ($archivo != "") {
                            $data_archivo = array(
                                'archivo' => $archivo,
                                    //'titulo' => $input['titulo_archivo'][$key]
                            );
                            $archivo_creado = Archivo::agregar($data_archivo);

                            $item->archivos()->attach($archivo_creado['data']->id);
                        }
                    }
                } else {
                    $data_archivo = array(
                        'archivo' => $input['archivo'],
                            //'titulo' => $input['titulo_archivo']
                    );
                    $archivo_creado = Archivo::agregar($data_archivo);
                    $item->archivos()->attach($archivo_creado['data']->id);
                    //$item->imagenes()->attach($imagen_creada['data']->miniatura()->id, array("destacado" => "A"));
                }
            }

            if (isset($input['imagen_portada']) && ($input['imagen_portada'] != "")) {

                if (isset($input['epigrafe_imagen_portada']) && ($input['epigrafe_imagen_portada'] != "")) {
                    $epigrafe_imagen_portada = $input['epigrafe_imagen_portada'];
                } else {
                    $epigrafe_imagen_portada = NULL;
                }

                if (isset($input['x']) && ($input['x'] != "")) {
                    $coordenadas = array("x" => $input['x'], "y" => $input['y'], "w" => $input['w'], "h" => $input['h']);
                } else {
                    $coordenadas = NULL;
                }

                $imagen_creada = Imagen::agregarImagen($input['imagen_portada'], $epigrafe_imagen_portada, $coordenadas);

                $item->imagenes()->attach($imagen_creada['data']->miniatura()->id, array("destacado" => "A"));
            }

            if (isset($input['imagen_portada_crop']) && ($input['imagen_portada_crop'] != "")) {
                if (is_array($input['imagen_portada_crop'])) {
                    $i = 0;
                    foreach ($input['imagen_portada_crop'] as $key => $imagen) {
                        if ($imagen != "") {

                            if (isset($input['imagen_portada_ampliada']) && ($input['imagen_portada_ampliada'] != "")) {
                                $ampliada = $input['imagen_portada_ampliada'][$key];
                            } else {
                                $ampliada = $imagen;
                            }

                            if (isset($input['epigrafe_imagen_portada']) && ($input['epigrafe_imagen_portada'] != "")) {
                                $epigrafe_imagen_portada = $input['epigrafe_imagen_portada'][$key];
                            } else {
                                $epigrafe_imagen_portada = NULL;
                            }

                            $imagen_crop = Imagen::agregarImagenCropped($imagen, $ampliada, $epigrafe_imagen_portada);

                            if (!$imagen_crop['error']) {
                                if ($i == 0) {
                                    $destacado = array(
                                        "destacado" => "A"
                                    );
                                } else {
                                    $destacado = array(
                                        "destacado" => NULL
                                    );
                                }
                                $item->imagenes()->attach($imagen_crop['data']->id, $destacado);
                            }
                            $i++;
                        }
                    }
                } else {
                    if (isset($input['imagen_portada_ampliada']) && ($input['imagen_portada_ampliada'] != "")) {
                        $ampliada = $input['imagen_portada_ampliada'];
                    } else {
                        $ampliada = $input['imagen_portada_crop'];
                    }

                    if (isset($input['epigrafe_imagen_portada']) && ($input['epigrafe_imagen_portada'] != "")) {
                        $epigrafe_imagen_portada = $input['epigrafe_imagen_portada'];
                    } else {
                        $epigrafe_imagen_portada = NULL;
                    }

                    $imagen_crop = Imagen::agregarImagenCropped($input['imagen_portada_crop'], $ampliada, $epigrafe_imagen_portada);

                    $item->imagenes()->attach($imagen_crop['data']->id, array("destacado" => "A"));
                }
            }

            if (isset($input['video']) && ($input['video'] != "")) {
                if (is_array($input['video'])) {
                    foreach ($input['video'] as $key => $video) {
                        if ($video != "") {

                            $dataUrl = parse_url($video);

                            if (in_array($dataUrl['host'], ['vimeo.com', 'www.vimeo.com'])) {
                                $hosts = array('vimeo.com', 'www.vimeo.com');

                                if (Video::validarUrlVimeo($video, $hosts)['estado']) {
                                    $data_video = array(
                                        'ID_video' => substr($dataUrl['path'], 1),
                                            //'titulo' => $input['titulo_archivo'][$key]
                                    );
                                    $video_creado = Video::agregarVimeo($data_video);

                                    $item->videos()->attach($video_creado['data']->id);
                                }
                            } else {
                                $hosts = array('youtube.com', 'www.youtube.com');
                                $paths = array('/watch');

                                if (Video::validarUrl($video, $hosts, $paths)['estado']) {
                                    if ($ID_video = Youtube::parseVIdFromURL($video)) {

                                        $data_video = array(
                                            'ID_video' => $ID_video,
                                                //'titulo' => $input['titulo_archivo'][$key]
                                        );
                                        $video_creado = Video::agregarYoutube($data_video);

                                        $item->videos()->attach($video_creado['data']->id);
                                    }
                                }
                            }
                        }
                    }
                } else {

                    $dataUrl = parse_url(Input::get('video'));

                    if (in_array($dataUrl['host'], ['vimeo.com', 'www.vimeo.com'])) {
                        $hosts = array('vimeo.com', 'www.vimeo.com');

                        if (Video::validarUrlVimeo(Input::get('video'), $hosts)['estado']) {
                            $data_video = array(
                                'ID_video' => substr($dataUrl['path'], 1),
                                    //'titulo' => $input['titulo_archivo'][$key]
                            );
                            $video_creado = Video::agregarVimeo($data_video);

                            $item->videos()->attach($video_creado['data']->id);
                        }
                    } else {
                        $hosts = array('youtube.com', 'www.youtube.com');
                        $paths = array('/watch');

                        if (Video::validarUrl(Input::get('video'), $hosts, $paths)['estado']) {
                            if ($ID_video = Youtube::parseVIdFromURL(Input::get('video'))) {

                                $data_video = array(
                                    'ID_video' => $ID_video,
                                        //'titulo' => $input['titulo_archivo'][$key]
                                );
                                $video_creado = Video::agregarYoutube($data_video);

                                $item->videos()->attach($video_creado['data']->id);
                            }
                        }
                    }
                }
            }


            //Le asocia la categoria en caso que se haya elegido alguna
            if (isset($input['categoria_id']) && ($input['categoria_id'] != "")) {
                $item->categorias()->attach($input['categoria_id']);
            }

            $secciones = array();

            if (isset($input['secciones']) && (is_array($input['secciones'])) && (count($input['secciones']) > 0)) {
                $secciones = $input['secciones'];
            }

            if (isset($input['seccion_id']) && ($input['seccion_id'] != "")) {
                array_push($secciones, $input['seccion_id']);
            }

            //Le asocia la seccion y por lo tanto la categoria correspondiente
            //if ($input['seccion_id'] != "") {
            if (count($secciones) > 0) {

                if (isset($input['item_destacado'])) {
                    switch ($input['item_destacado']) {
                        case 'A':
                            $destacado = 'A';
                            break;
                        case 'N':
                            $destacado = 'N';
                            break;
                        case 'O':
                            $destacado = 'O';
                            break;
                        default :
                            $destacado = NULL;
                            break;
                    }
                } else {
                    $destacado = NULL;
                }

                $info = array(
                    'estado' => 'A',
                    'destacado' => $destacado
                );

                $item->secciones()->attach($secciones, $info);

                foreach ($secciones as $seccion_id) {
                    //ME QUEDO CON LA SECCION CORRESPONDIENTE
                    //$seccion = Seccion::find($input['seccion_id']);
                    $seccion = Seccion::find($seccion_id);

                    //ME QUEDO CON EL MENU AL CUAL PERTENECE LA SECCION

                    foreach ($seccion->menu as $menu) {
                        $menu_id = $menu->id;
                    }

                    $menu = Menu::find($menu_id);

                    //ME QUEDO CON LA CATEGORIA AL CUAL PERTENECE EL MENU
                    foreach ($menu->categorias as $categoria) {
                        $categoria_id = $categoria->id;
                    }

                    //IMPACTO AL ITEM CON LA CATEGORIA CORRESPONDIENTE

                    if (isset($categoria_id)) {
                        $item->categorias()->attach($categoria_id);
                    }
                }
            }

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = 'Producto creado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $item;
        }

        return $respuesta;
    }

    public static function editarItem($input) {
        $respuesta = array();

        $reglas = array(
                //'titulo' => array('max:50', 'unique:item,titulo,' . $input['id']),
                //'imagen_portada_crop' => array('required')
        );

        if (isset($input['file']) && ($input['file'] != "") && (!is_array($input['file']))) {
            $reglas['x'] = array('required');
            $reglas['y'] = array('required');
            $reglas['h'] = array('required');
            $reglas['w'] = array('required');
        }

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = '';
            $respuesta['error'] = true;
        } else {

            $item = Item::find($input['id']);

            $item_anterior = array(
                'item_id' => $item->id,
                'titulo' => $item->titulo,
                'descripcion' => $item->descripcion,
                'url' => $item->url,
                'fecha_modificacion' => date("Y-m-d H:i:s"),
                'usuario_id' => Auth::user()->id
            );

            if ($input['titulo'] == "") {
                $url = $item->url;
            } else {
                $url = Str::slug($input['titulo']);
            }

            $item->titulo = $input['titulo'];
            $item->descripcion = $input['descripcion'];
            $item->url = $url;
            $item->fecha_modificacion = date("Y-m-d H:i:s");

            $item->save();

            $item_modificacion = DB::table('item_modificacion')->insert($item_anterior);

            if (isset($input['file']) && ($input['file'] != "")) {
                if (is_array($input['file'])) {
                    foreach ($input['file'] as $key => $imagen) {
                        if ($imagen != "") {
                            $imagen_creada = Imagen::agregarImagen($imagen, $input['epigrafe'][$key]);

                            if (!$imagen_creada['error']) {
                                if (isset($input['destacado']) && ($input['destacado'] == $key)) {
                                    $destacado = array(
                                        "destacado" => "A"
                                    );
                                } else {
                                    $destacado = array(
                                        "destacado" => NULL
                                    );
                                }
                                $item->imagenes()->attach($imagen_creada['data']->miniatura()->id, $destacado);
                            }
                        }
                    }
                } else {
                    $coordenadas = array("x" => $input['x'], "y" => $input['y'], "w" => $input['w'], "h" => $input['h']);
                    $imagen_creada = Imagen::agregarImagen($input['file'], $input['epigrafe'], $coordenadas);

                    $item->imagenes()->attach($imagen_creada['data']->miniatura()->id, array("destacado" => "A"));
                    //$item->imagenes()->attach($imagen_creada['data']->->id, array("destacado" => "A"));
                }
            }

            if (isset($input['archivo']) && ($input['archivo'] != "")) {
                if (is_array($input['archivo'])) {
                    foreach ($input['archivo'] as $key => $archivo) {
                        if ($archivo != "") {
                            $data_archivo = array(
                                'archivo' => $archivo,
                                    //'titulo' => $input['titulo_archivo'][$key]
                            );
                            $archivo_creado = Archivo::agregar($data_archivo);

                            $item->archivos()->attach($archivo_creado['data']->id);
                        }
                    }
                } else {
                    $data_archivo = array(
                        'archivo' => $input['archivo'],
                            //'titulo' => $input['titulo_archivo']
                    );
                    $archivo_creado = Archivo::agregar($data_archivo);
                    $item->archivos()->attach($archivo_creado['data']->id);
                    //$item->imagenes()->attach($imagen_creada['data']->miniatura()->id, array("destacado" => "A"));
                }
            }

            if (isset($input['imagen_id']) && ($input['imagen_id'] != "")) {
                $data_imagen = array(
                    'id' => $input['imagen_id'],
                    'epigrafe' => $input['epigrafe']
                );

                $imagen_editada = Imagen::editar($data_imagen);
            }

            if (isset($input['imagen_portada_editar']) && ($input['imagen_portada_editar'] != "")) {
                $data_imagen = array(
                    'id' => $input['imagen_portada_editar'],
                    'epigrafe' => $input['epigrafe_imagen_portada_editar']
                );

                $imagen_editada = Imagen::editar($data_imagen);
            }

            if (isset($input['imagen_portada']) && ($input['imagen_portada'] != "")) {
                if (isset($input['epigrafe_imagen_portada']) && ($input['epigrafe_imagen_portada'] != "")) {
                    $epigrafe_imagen_portada = $input['epigrafe_imagen_portada'];
                } else {
                    $epigrafe_imagen_portada = NULL;
                }

                if (isset($input['x']) && ($input['x'])) {
                    $coordenadas = array("x" => $input['x'], "y" => $input['y'], "w" => $input['w'], "h" => $input['h']);
                } else {
                    $coordenadas = NULL;
                }

                $imagen_creada = Imagen::agregarImagen($input['imagen_portada'], $epigrafe_imagen_portada, $coordenadas);

                $item->imagenes()->attach($imagen_creada['data']->miniatura()->id, array("destacado" => "A"));
            }

            if (isset($input['imagenes_editar']) && ($input['imagenes_editar'] != "")) {
                foreach ($input['imagenes_editar'] as $key => $imagen) {
                    if ($imagen != "") {

                        $datos = array(
                            'id' => $imagen,
                            'epigrafe' => $input['epigrafe_imagen_editar'][$key]
                        );

                        $imagen_modificada = Imagen::editar($datos);
                    }
                }
            }

            if (isset($input['imagen_crop_editar']) && ($input['imagen_crop_editar'] != "")) {
                if (is_array($input['imagen_crop_editar'])) {
                    foreach ($input['imagen_crop_editar'] as $key => $imagen) {
                        if ($imagen != "") {

                            $datos = array(
                                'id' => $imagen,
                                'epigrafe' => $input['epigrafe_imagen_crop_editar'][$key]
                            );

                            $imagen_crop = Imagen::editar($datos);
                        }
                    }
                }
            }

            if (isset($input['imagen_portada_crop']) && ($input['imagen_portada_crop'] != "")) {
                if (is_array($input['imagen_portada_crop'])) {
                    foreach ($input['imagen_portada_crop'] as $key => $imagen) {
                        if ($imagen != "") {

                            if (isset($input['imagen_portada_ampliada']) && ($input['imagen_portada_ampliada'] != "")) {
                                $ampliada = $input['imagen_portada_ampliada'][$key];
                            } else {
                                $ampliada = $imagen;
                            }

                            if (isset($input['epigrafe_imagen_portada']) && ($input['epigrafe_imagen_portada'] != "")) {
                                $epigrafe_imagen_portada = $input['epigrafe_imagen_portada'][$key];
                            } else {
                                $epigrafe_imagen_portada = NULL;
                            }

                            $imagen_crop = Imagen::agregarImagenCropped($imagen, $ampliada, $epigrafe_imagen_portada);

                            if (!$imagen_crop['error']) {

                                $destacado = array(
                                    "destacado" => NULL
                                );

                                $item->imagenes()->attach($imagen_crop['data']->id, $destacado);
                            }
                        }
                    }
                } else {
                    if (isset($input['imagen_portada_ampliada']) && ($input['imagen_portada_ampliada'] != "")) {
                        $ampliada = $input['imagen_portada_ampliada'];
                    } else {
                        $ampliada = $input['imagen_portada_crop'];
                    }

                    if (isset($input['epigrafe_imagen_portada']) && ($input['epigrafe_imagen_portada'] != "")) {
                        $epigrafe_imagen_portada = $input['epigrafe_imagen_portada'];
                    } else {
                        $epigrafe_imagen_portada = NULL;
                    }

                    $imagen_crop = Imagen::agregarImagenCropped($input['imagen_portada_crop'], $ampliada, $epigrafe_imagen_portada);

                    $item->imagenes()->attach($imagen_crop['data']->id, array("destacado" => "A"));
                }
            }

            if (isset($input['video']) && ($input['video'] != "")) {
                if (is_array($input['video'])) {
                    foreach ($input['video'] as $key => $video) {
                        if ($video != "") {

                            $dataUrl = parse_url($video);

                            if (in_array($dataUrl['host'], ['vimeo.com', 'www.vimeo.com'])) {
                                $hosts = array('vimeo.com', 'www.vimeo.com');

                                if (Video::validarUrlVimeo($video, $hosts)['estado']) {
                                    $data_video = array(
                                        'ID_video' => substr($dataUrl['path'], 1),
                                            //'titulo' => $input['titulo_archivo'][$key]
                                    );
                                    $video_creado = Video::agregarVimeo($data_video);

                                    $item->videos()->attach($video_creado['data']->id);
                                }
                            } else {
                                $hosts = array('youtube.com', 'www.youtube.com');
                                $paths = array('/watch');

                                if (Video::validarUrl($video, $hosts, $paths)['estado']) {
                                    if ($ID_video = Youtube::parseVIdFromURL($video)) {

                                        $data_video = array(
                                            'ID_video' => $ID_video,
                                                //'titulo' => $input['titulo_archivo'][$key]
                                        );
                                        $video_creado = Video::agregarYoutube($data_video);

                                        $item->videos()->attach($video_creado['data']->id);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $dataUrl = parse_url(Input::get('video'));

                    if (in_array($dataUrl['host'], ['vimeo.com', 'www.vimeo.com'])) {
                        $hosts = array('vimeo.com', 'www.vimeo.com');

                        if (Video::validarUrlVimeo(Input::get('video'), $hosts)['estado']) {
                            $data_video = array(
                                'ID_video' => substr($dataUrl['path'], 1),
                                    //'titulo' => $input['titulo_archivo'][$key]
                            );
                            $video_creado = Video::agregarVimeo($data_video);

                            $item->videos()->attach($video_creado['data']->id);
                        }
                    } else {
                        $hosts = array('youtube.com', 'www.youtube.com');
                        $paths = array('/watch');

                        if (Video::validarUrl(Input::get('video'), $hosts, $paths)['estado']) {
                            if ($ID_video = Youtube::parseVIdFromURL(Input::get('video'))) {

                                $data_video = array(
                                    'ID_video' => $ID_video,
                                        //'titulo' => $input['titulo_archivo'][$key]
                                );
                                $video_creado = Video::agregarYoutube($data_video);

                                $item->videos()->attach($video_creado['data']->id);
                            }
                        }
                    }
                }
            }

            $secciones = array();

            if (isset($input['secciones']) && (is_array($input['secciones'])) && (count($input['secciones']) > 0)) {
                $secciones = $input['secciones'];
            }

            if (isset($input['seccion_id']) && ($input['seccion_id'] != "")) {
                array_push($secciones, $input['seccion_id']);
            }

            //Le asocia la seccion y por lo tanto la categoria correspondiente
            //if ($input['seccion_id'] != "") {
            if (count($secciones) > 0) {

                foreach ($item->secciones as $seccion) {
                    $data_borrar = array(
                        'item_id' => $item->id,
                        'seccion_id' => $seccion->id
                    );

                    $item->borrarItemSeccion($data_borrar);
                }

                if (isset($input['item_destacado'])) {
                    switch ($input['item_destacado']) {
                        case 'A':
                            $destacado = 'A';
                            break;
                        case 'N':
                            $destacado = 'N';
                            break;
                        case 'O':
                            $destacado = 'O';
                            break;
                        default :
                            $destacado = NULL;
                            break;
                    }
                } else {
                    $destacado = NULL;
                }

                $info = array(
                    'estado' => 'A',
                    'destacado' => $destacado
                );

                $item->secciones()->attach($secciones, $info);

                foreach ($secciones as $seccion_id) {
                    //ME QUEDO CON LA SECCION CORRESPONDIENTE
                    //$seccion = Seccion::find($input['seccion_id']);
                    $seccion = Seccion::find($seccion_id);

                    //ME QUEDO CON EL MENU AL CUAL PERTENECE LA SECCION

                    foreach ($seccion->menu as $menu) {
                        $menu_id = $menu->id;
                    }

                    $menu = Menu::find($menu_id);

                    //ME QUEDO CON LA CATEGORIA AL CUAL PERTENECE EL MENU
                    foreach ($menu->categorias as $categoria) {
                        $categoria_id = $categoria->id;
                    }

                    //IMPACTO AL ITEM CON LA CATEGORIA CORRESPONDIENTE

                    if (isset($categoria_id)) {
                        $item->categorias()->attach($categoria_id);
                    }
                }
            }
            /*
              if (isset($input['secciones']) && (count($input['secciones']) > 0)) {
              foreach ($item->secciones as $seccion) {
              $data_borrar = array(
              'item_id' => $item->id,
              'seccion_id' => $seccion->id
              );

              $item->borrarItemSeccion($data_borrar);
              }

              foreach ($input['secciones'] as $secc) {
              $destacado = NULL;
              if (isset($input['item_destacado']) && ($input['item_destacado'] != "")) {
              if ($input['item_destacado'] == "A") {
              $destacado = 'A';
              }
              }

              $info = array(
              'estado' => 'A',
              'destacado' => $destacado
              );

              $item->secciones()->attach($secc, $info);

              //ME QUEDO CON LA SECCION CORRESPONDIENTE
              //$seccion = Seccion::find($input['seccion_id']);
              $seccion = Seccion::find($secc);

              //ME QUEDO CON EL MENU AL CUAL PERTENECE LA SECCION

              foreach ($seccion->menu as $menu) {
              $menu_id = $menu->id;
              }

              $menu = Menu::find($menu_id);

              //ME QUEDO CON LA CATEGORIA AL CUAL PERTENECE EL MENU
              foreach ($menu->categorias as $categoria) {
              $categoria_id = $categoria->id;
              }

              //IMPACTO AL ITEM CON LA CATEGORIA CORRESPONDIENTE

              if (isset($categoria_id)) {
              $item->categorias()->attach($categoria_id);
              }
              }
              }
             * 
             */
            /*
              if (isset($input['seccion_nueva_id']) && ($input['seccion_nueva_id'] != "")) {
              if ($item->seccionItem()->id != $input['seccion_nueva_id']) {
              $data_borrar = array(
              'item_id' => $item->id,
              'seccion_id' => $item->seccionItem()->id
              );
              $item->borrarItemSeccion($data_borrar);

              $item->secciones()->attach($input['seccion_nueva_id'], array('estado' => 'A'));
              }
              }

              if (isset($input['item_destacado']) && ($input['item_destacado'] != "")) {
              $data_item = array(
              'item_id' => $item->id,
              'seccion_id' => $item->seccionItem()->id
              );

              if ($input['item_destacado'] == "A") {
              $destacado = 'A';
              } else {
              $destacado = NULL;
              }

              DB::table('item_seccion')->where($data_item)->update(['destacado' => $destacado]);
              } else {
              $data_item = array(
              'item_id' => $item->id,
              'seccion_id' => $item->seccionItem()->id
              );
              DB::table('item_seccion')->where($data_item)->update(['destacado' => NULL]);
              }
             * 
             */

            $respuesta['mensaje'] = 'Producto modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $item;
        }

        return $respuesta;
    }

    public static function borrarItem($input) {
        $respuesta = array();

        $reglas = array('id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta ['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $item = Item::find($input['id']);

            $item->fecha_baja = date("Y-m-d H:i:s");
            $item->titulo = $item->titulo . "-borrado";
            $item->url = $item->url . "-borrado";
            $item->estado = 'B';
            $item->usuario_id_baja = Auth::user()->id;

            $item->save();

            
            $respuesta['mensaje'] = $item->tipo()['tipo_singular'].' eliminado.';
            
            //$respuesta['mensaje'] = 'Producto eliminado';
            $respuesta['error'] = false;
            $respuesta['data'] = $item;
        }

        return $respuesta;
    }

    public static function borrarItemSeccion($input) {
        $respuesta = array();

        $reglas = array(
            'item_id' => array('integer'),
            'seccion_id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $baja_item_seccion = DB::table('item_seccion')->where($input)->update(array(
                'estado' => 'B'));

            $it = Item::find($input['item_id']);
            
            $respuesta['mensaje'] = $it->tipo()['tipo_singular'].' eliminado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $baja_item_seccion;
        }

        return $respuesta;
    }

    public static function ordenarItemSeccion($item_id, $orden, $seccion_id) {
        $respuesta = array();

        $datos = array(
            'item_id' => $item_id,
            'orden' => $orden,
            'seccion_id' => $seccion_id
        );

        $reglas = array('item_id' => array('integer'),
            'orden' => array('integer'),
            'seccion_id' => array('integer')
        );

        $validator = Validator::make($datos, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {


            $input = array(
                'item_id' => $item_id,
                'seccion_id' => $seccion_id
            );

            $item = DB::table('item_seccion')->where(
                            $input)->update(array('orden' => $orden));
            
            $it = Item::find($item_id);

            $respuesta['mensaje'] = 'Los ' . $it->tipo()['tipo_plural'] . ' han sido ordenados.';
            $respuesta['error'] = false;
            $respuesta['data'] = $item;
        }

        return $respuesta;
    }

    public static function destacar($input) {
        $respuesta = array();

        $reglas = array(
            'item_id' => array('integer'),
            'seccion_id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $baja_item_seccion = DB::table('item_seccion')->where($input)->update(array(
                'destacado' => 'A'));

            $respuesta['mensaje'] = 'Producto destacado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $baja_item_seccion;
        }

        return $respuesta;
    }

    public static function ponerNuevo($input) {
        $respuesta = array();

        $reglas = array(
            'item_id' => array('integer'),
                //'seccion_id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $item = Item::find($input['item_id']);

            foreach ($item->secciones as $seccion) {

                $info = array(
                    'item_id' => $item->id,
                    'seccion_id' => $seccion->id
                );

                $baja_item_seccion = DB::table('item_seccion')->where($info)->update(array(
                    'destacado' => 'N'));
            }

            $respuesta['mensaje'] = 'Producto nuevo.';
            $respuesta['error'] = false;
            $respuesta['data'] = $baja_item_seccion;
        }

        return $respuesta;
    }

    public static function ponerOferta($input) {
        $respuesta = array();

        $reglas = array(
            'item_id' => array('integer'),
                //'seccion_id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $item = Item::find($input['item_id']);

            foreach ($item->secciones as $seccion) {

                $info = array(
                    'item_id' => $item->id,
                    'seccion_id' => $seccion->id
                );

                $baja_item_seccion = DB::table('item_seccion')->where($info)->update(array(
                    'destacado' => 'O'));
            }

            $respuesta['mensaje'] = 'Producto oferta.';
            $respuesta['error'] = false;
            $respuesta['data'] = $baja_item_seccion;
        }

        return $respuesta;
    }

    public static function quitarDestacado($input) {
        $respuesta = array();

        $reglas = array(
            'item_id' => array('integer'),
                //'seccion_id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $item = Item::find($input['item_id']);

            foreach ($item->secciones as $seccion) {

                $info = array(
                    'item_id' => $item->id,
                    'seccion_id' => $seccion->id
                );


                $baja_item_seccion = DB::table('item_seccion')->where($info)->update(array('destacado' =>
                    NULL));
            }

            $respuesta['mensaje'] = '';
            $respuesta['error'] = false;
            $respuesta['data'] = $baja_item_seccion;
        }

        return $respuesta;
    }

    public function seccionItem() {
        $seccion = NULL;
        foreach ($this->secciones as $secciones) {
            $seccion = $secciones;
        }

        return $seccion;
    }

    //Me quedo con las categorias a las que pertenece el Item
    public function categorias() {
        return $this->belongsToMany('Categoria', 'item_categoria', 'item_id', 'categoria_id');
    }

    //Me quedo con las secciones a las que pertenece el Item
    public function secciones() {
        return $this->belongsToMany('Seccion', 'item_seccion', 'item_id', 'seccion_id')->where('item_seccion.estado', 'A');
    }

    public function imagenes() {
        return $this->belongsToMany('Imagen', 'item_imagen', 'item_id', 'imagen_id')->where('imagen.estado', 'A')->whereNull('item_imagen.destacado')->orWhere('item_imagen.destacado', '<>', 'A')->orderBy('item_imagen.orden')->orderBy('imagen.id', 'DESC');
    }

    public function imagenes_producto() {
        return $this->belongsToMany('Imagen', 'item_imagen', 'item_id', 'imagen_id')->where('imagen.estado', 'A')->orderBy('item_imagen.destacado', 'DESC')->get();
    }

    public function imagenes_producto_editar() {
        return $this->belongsToMany('Imagen', 'item_imagen', 'item_id', 'imagen_id')->where('imagen.estado', 'A')->whereNull('item_imagen.destacado')->orWhere('item_imagen.destacado', '<>', 'A')->get();
    }

    public function archivos() {
        return $this->belongsToMany('Archivo', 'item_archivo', 'item_id', 'archivo_id')->where('archivo.estado', 'A')->whereNull('item_archivo.destacado')->orWhere('item_archivo.destacado', '<>', 'A');
    }

    public function videos() {
        return $this->belongsToMany('Video', 'item_video', 'item_id', 'video_id')->where('video.estado', 'A')->whereNull('item_video.destacado')->orWhere('item_video.destacado', '<>', 'A');
    }

    public function obtener_destacada() {
        return DB::table('item_imagen')
                        ->join('imagen', 'item_imagen.imagen_id', '=', 'imagen.id')
                        ->where('item_imagen.item_id', $this->id)
                        ->where('item_imagen.destacado', 'A')
                        ->where('imagen.estado', 'A')
                        ->get();
    }

    public function imagen_destacada() {
        $img = NULL;


        foreach ($this->obtener_destacada() as $image) {
            $img = Imagen::find($image->id);
        }

        return $img;
    }

    public function destacado() {
        if (DB::table('item_seccion')
                        ->join('seccion', 'item_seccion.seccion_id', '=', 'seccion.id')
                        ->join('item', 'item_seccion.item_id', '=', 'item.id')
                        ->where('item_seccion.estado', 'A')
                        ->where('item_seccion.item_id', $this->id)
                        ->where('item.estado', 'A')
                        ->where('seccion.estado', 'A')
                        ->where('item_seccion.destacado', 'A')
                        ->get()) {
            return true;
        }
        return false;
    }

    public function texto() {
        return Texto::where('item_id', $this->id)->first();
    }

    public function html() {
        return TextoHtml::where('item_id', $this->id)->first();
    }

    public function galeria() {
        return Galeria::where('item_id', $this->id)->first();
    }

    public function producto() {
        return Producto::where('item_id', $this->id)->first();
    }

    public function portfolio() {
        return Portfolio::where('item_id', $this->id)->first();
    }

    public function muestra() {
        return Muestra::where('item_id', $this->id)->first();
    }

    public function tipo() {
        $result = array(
            'tipo_singular' => 'item',
            'tipo_plural' => 'items'
        );

        if (!is_null($this->texto())) {
            $result['tipo_singular'] = 'texto';
            $result['tipo_plural'] = 'textos';
        } elseif (!is_null($this->html())) {
            $result['tipo_singular'] = 'html';
            $result['tipo_plural'] = 'htmls';
        } elseif (!is_null($this->galeria())) {
            $result['tipo_singular'] = 'galería';
            $result['tipo_plural'] = 'galerias';
        } elseif (!is_null($this->producto())) {
            $result['tipo_singular'] = 'producto';
            $result['tipo_plural'] = 'productos';
        } elseif (!is_null($this->portfolio())) {
            $result['tipo_singular'] = 'obra';
            $result['tipo_plural'] = 'obras';
        } elseif (!is_null($this->muestra())) {
            $result['tipo_singular'] = 'muestra';
            $result['tipo_plural'] = 'muestras';
        }

        return $result;
    }

}
