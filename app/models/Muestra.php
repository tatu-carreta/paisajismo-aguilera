<?php

class Muestra extends Item {

    //Tabla de la BD
    protected $table = 'muestra';
    //Atributos que van a ser modificables
    protected $fillable = array('item_id');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregar($input) {
        //Lo crea definitivamente

        $respuesta = array();

        //Se definen las reglas con las que se van a validar los datos
        //del PRODUCTO
        $reglas = array(
            'titulo' => array('required', 'max:50', 'unique:item_lang'),
            'seccion_id' => array('integer'),
            'imagen_portada_crop' => array('required'),
        );

        //Se realiza la validación
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {

            $messages = $validator->messages();
            if ($messages->has('titulo')) {
                $respuesta['mensaje'] = 'El título de la muestra contiene más de 50 caracteres o ya existe.';
            } elseif ($messages->has('imagen_portada_crop')) {
                $respuesta['mensaje'] = 'Se olvidó de guardar la imagen recortada.';
            } else {
                $respuesta['mensaje'] = 'Los datos necesarios para la muestra son erróneos.';
            }

            //Si está todo mal, carga lo que corresponde en el mensaje.

            $respuesta['error'] = true;
        } else {

            $ok = false;
            if (isset($input['video']) && ($input['video'] != "")) {
                if (is_array($input['video'])) {
                    foreach ($input['video'] as $key => $video) {
                        if ($video != "") {

                            $dataUrl = parse_url($video);

                            if (in_array($dataUrl['host'], ['vimeo.com', 'www.vimeo.com'])) {
                                $hosts = array('vimeo.com', 'www.vimeo.com');

                                if (Video::validarUrlVimeo($video, $hosts)['estado']) {
                                    $ok = true;
                                }
                            } else {
                                $hosts = array('youtube.com', 'www.youtube.com');
                                $paths = array('/watch');

                                if (Video::validarUrl($video, $hosts, $paths)['estado']) {
                                    if ($ID_video = Youtube::parseVIdFromURL($video)) {
                                        $ok = true;
                                    }
                                }
                            }
                        } else {
                            $ok = true;
                            break;
                        }
                    }
                } else {
                    $dataUrl = parse_url($input['video']);

                    if (in_array($dataUrl['host'], ['vimeo.com', 'www.vimeo.com'])) {
                        $hosts = array('vimeo.com', 'www.vimeo.com');

                        if (Video::validarUrlVimeo($input['video'], $hosts)['estado']) {
                            $ok = true;
                        }
                    } else {
                        $hosts = array('youtube.com', 'www.youtube.com');
                        $paths = array('/watch');

                        if (Video::validarUrl($input['video'], $hosts, $paths)['estado']) {
                            if ($ID_video = Youtube::parseVIdFromURL($input['video'])) {
                                $ok = true;
                            }
                        }
                    }
                }
            } else {
                $ok = true;
            }

            if ($ok) {

                if (isset($input['descripcion'])) {

                    $input['descripcion'] = $input['descripcion'];
                } else {
                    $input['descripcion'] = NULL;
                }


                $item = Item::agregarItem($input);

                if (isset($input['cuerpo'])) {

                    $cuerpo = $input['cuerpo'];
                } else {
                    $cuerpo = NULL;
                }

                if (!$item['error']) {

                    $muestra = static::create(['item_id' => $item['data']->id]);

                    $datos_lang = array(
                        'cuerpo' => $cuerpo,
                    );

                    $idiomas = Idioma::where('estado', 'A')->get();

                    foreach ($idiomas as $idioma) {
                        /*
                          if ($idioma->codigo != Config::get('app.locale')) {
                          $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
                          }
                         * 
                         */
                        $muestra->idiomas()->attach($idioma->id, $datos_lang);
                    }

                    $respuesta['data'] = $muestra;
                    $respuesta['error'] = false;
                    $respuesta['mensaje'] = "Muestra creada.";
                } else {
                    $respuesta['error'] = true;
                    $respuesta['mensaje'] = "La muestra no pudo ser creada. Compruebe los campos.";
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = "Problema en la/s url de video cargada.";
            }
        }
        return $respuesta;
    }

    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
            'titulo' => array('required', 'max:50', 'unique:item_lang,titulo,' . $input['id']),
        );

        if (isset($input['imagen_portada_crop'])) {
            $reglas['imagen_portada_crop'] = array('required');
        }

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $messages = $validator->messages();
            if ($messages->has('titulo')) {
                $respuesta['mensaje'] = 'El título de la obra contiene más de 50 caracteres o ya existe.';
            } elseif ($messages->has('imagen_portada_crop')) {
                $respuesta['mensaje'] = 'Se olvidó de guardar la imagen recortada.';
            } else {
                $respuesta['mensaje'] = 'Los datos necesarios para la obra son erróneos.';
            }
            $respuesta['error'] = true;
        } else {
            $ok = false;
            if (isset($input['video']) && ($input['video'] != "")) {
                if (is_array($input['video'])) {
                    foreach ($input['video'] as $key => $video) {
                        if ($video != "") {

                            $dataUrl = parse_url($video);

                            if (in_array($dataUrl['host'], ['vimeo.com', 'www.vimeo.com'])) {
                                $hosts = array('vimeo.com', 'www.vimeo.com');

                                if (Video::validarUrlVimeo($video, $hosts)['estado']) {
                                    $ok = true;
                                }
                            } else {
                                $hosts = array('youtube.com', 'www.youtube.com');
                                $paths = array('/watch');

                                if (Video::validarUrl($video, $hosts, $paths)['estado']) {
                                    if ($ID_video = Youtube::parseVIdFromURL($video)) {
                                        $ok = true;
                                    }
                                }
                            }
                        } else {
                            $ok = true;
                            break;
                        }
                    }
                } else {
                    $dataUrl = parse_url($input['video']);

                    if (in_array($dataUrl['host'], ['vimeo.com', 'www.vimeo.com'])) {
                        $hosts = array('vimeo.com', 'www.vimeo.com');

                        if (Video::validarUrlVimeo($input['video'], $hosts)['estado']) {
                            $ok = true;
                        }
                    } else {
                        $hosts = array('youtube.com', 'www.youtube.com');

                        $paths = array('/watch');

                        if (Video::

                                validarUrl($input['video'], $hosts, $paths)['estado']) {
                            if ($ID_video = Youtube::parseVIdFromURL($input['video'])) {
                                $ok = true;
                            }
                        }
                    }
                }
            } else {
                $ok = true;
            }

            if ($ok) {

                $muestra = Muestra::find($input['muestra_id']);

                if (isset($input['cuerpo'])) {

                    $cuerpo = $input['cuerpo'];
                } else {
                    $cuerpo = NULL;
                }

//                $muestra->cuerpo = $cuerpo;
//
//                $muestra->save();

                $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

                $muestra_lang = Muestra::join('muestra_lang', 'muestra_lang.muestra_id', '=', 'muestra.id')->where('muestra_lang.lang_id', $lang->id)->where('muestra.id', $muestra->id)->first();

                $datos = array(
                    'cuerpo' => $cuerpo,
                );

                $muestra_modificacion = DB::table('muestra_lang')->where('id', $muestra_lang->id)->update($datos);


                if (isset($input['descripcion'])) {

                    $input['descripcion'] = $input['descripcion'];
                } else {
                    $input['descripcion'] = NULL;
                }

                $item = Item::editarItem($input);

                $respuesta['mensaje'] = 'Muestra modificada.';
                $respuesta['error'] = false;
                $respuesta['data'] = $muestra;
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = "Problema en la/s url de video cargada.";
            }
        }
        return $respuesta;
    }

    public function item() {
        return Item::find($this->item_id);
    }

    public static function buscar($item_id) {
        return Muestra::where('item_id', $item_id)->first();
    }

    public function idiomas() {
        return $this->belongsToMany('Idioma', 'muestra_lang', 'muestra_id', 'lang_id');
    }

    public function lang() {
        $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

        $muestra = Muestra::join('muestra_lang', 'muestra_lang.muestra_id', '=', 'muestra.id')->where('muestra_lang.lang_id', $lang->id)->where('muestra.id', $this->id)->first();

        if (is_null($muestra)) {
            echo "Por null";
            $lang = Idioma::where('codigo', 'es')->where('estado', 'A')->first();
            $muestra = Muestra::join('muestra_lang', 'muestra_lang.muestra_id', '=', 'muestra.id')->where('muestra_lang.lang_id', $lang->id)->where('muestra.id', $this->id)->first();
        }

        return $muestra;
    }

}
