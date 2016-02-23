<?php

// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;

class Imagen extends Eloquent {

    //Tabla de la BD
    protected $table = 'imagen';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre', 'carpeta', 'tipo', 'ampliada', 'estado', 'fecha_carga', 'fecha_modificacion', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public static function agregarImagen($imagen = null, $epigrafe = null, $coordenadas = null) {

        $respuesta = array();

        $datos = array(
            'imagen' => $imagen,
            'epigrafe' => $epigrafe
        );

        $rules = array(
            'imagen' => array('mimes:jpeg,png,gif'),
        );

        $validator = Validator::make($datos, $rules);

        if ($validator->fails()) {
            //return Response::make($validator->errors->first(), 400);
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = 'no pasa';
        } else {


            $file = $imagen;

            $count = count($file->getClientOriginalName()) - 4;

            $filename = Str::limit(Str::slug($file->getClientOriginalName()), $count, "");
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
            //$extension = File::extension($file['name']);

            $carpeta = '/uploads/';
            $directory = public_path() . $carpeta;
            //$filename = sha1(time() . Hash::make($filename) . time()) . ".{$extension}";
            //Pregunto para que no se repita el nombre de la imagen
            if (!is_null(Imagen::imagenPorNombre($filename . ".{$extension}"))) {

                $filename = $filename . "(" . Str::limit(sha1(time()), 3, "") . ")" . ".{$extension}";
            } else {
                $filename = $filename . ".{$extension}";
            }

            //$upload_success = $file->move($directory, $filename);
            //resize(width, height)

            if (Image::make($file)->resize(null, 800, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($directory . $filename)) {
                $datos = array(
                    'nombre' => $filename,
                    //'epigrafe' => $epigrafe,
                    'carpeta' => $carpeta,
                    'tipo' => 'G',
                    'ampliada' => '',
                    'estado' => 'A',
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'usuario_id_carga' => Auth::user()->id
                );

                $imagen = static::create($datos);

                $datos_lang = array(
                    'epigrafe' => $epigrafe,
                    'estado' => 'A',
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'usuario_id_carga' => Auth::user()->id
                );

                $idiomas = Idioma::where('estado', 'A')->get();

                foreach ($idiomas as $idioma) {
                    /*
                      if ($idioma->codigo != Config::get('app.locale')) {
                      $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
                      }
                     * 
                     */
                    $imagen->idiomas()->attach($idioma->id, $datos_lang);
                }

                $temporary = Image::make($file)->resize(null, 250, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->save(public_path() . "/temporary/" . $filename);

                $imagen_chica = Imagen::agregarImagenChica($filename, $epigrafe, $imagen->id, $coordenadas);

                //Mensaje correspondiente a la agregacion exitosa
                $respuesta['mensaje'] = 'Imagen creada.';
                $respuesta['error'] = false;
                $respuesta['data'] = $imagen;
                //return Response::json('success', 200);
            } else {
                //Mensaje correspondiente a la agregacion exitosa
                $respuesta['mensaje'] = 'Imagen errónea.';
                $respuesta['error'] = true;
                $respuesta['data'] = null;
                //return Response::json('error', 400);
            }
        }

        return $respuesta;
    }

    public static function agregarImagenChica($imagen = null, $epigrafe = null, $imagen_ampliada, $coordenadas = null) {

        $respuesta = array();

        $rules = array(
                //'file' => 'image',
        );

        //$validator = Validator::make($imagen, $rules);
        //echo "HOLÑA -> " . $imagen->getRealPath();

        if (false) {//$validator->fails()) {
            //return Response::make($validation->errors->first(), 400);
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = 'no pasa';
        } else {

            $file = $imagen;
            //$filename = $file->getClientOriginalName();
            //$extension = $file->getClientOriginalExtension(); //if you need extension of the file
            //$extension = File::extension($file['name']);

            $carpeta = '/uploads/';
            $directory = public_path() . $carpeta;
            $filename = "small_" . $imagen; //sha1(time() . Hash::make($filename) . time()) . "_small" . ".{$extension}";
            /*
              if (dd(is_readable($directory . $file))) {
              echo "Es";
              } else {
              echo "NO ES";
              }
             * 
             */
            if (!is_null($coordenadas)) {
                $w = round($coordenadas['w']);
                $h = round($coordenadas['h']);
                $x = round($coordenadas['x']);
                $y = round($coordenadas['y']);

                $upload = Image::make(public_path() . "/temporary/" . $file)->crop($w, $h, $x, $y)->resize(null, 250, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($directory . $filename);
            } else {
                //para relacion height auto
                $upload = Image::make(public_path() . "/temporary/" . $file)->resize(null, 250, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($directory . $filename);
                //para cuadrado
                //$upload = Image::make(public_path() . "/temporary/" . $file)->resize(340, 340)->save($directory . $filename);
            }

            if ($upload) {
                $datos = array(
                    'nombre' => $filename,
                    //'epigrafe' => $epigrafe,
                    'carpeta' => $carpeta,
                    'tipo' => 'C',
                    'ampliada' => $imagen_ampliada,
                    'estado' => 'A',
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'usuario_id_carga' => Auth::user()->id
                );

                $imagen = static::create($datos);

                $datos_lang = array(
                    'epigrafe' => $epigrafe,
                    'estado' => 'A',
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'usuario_id_carga' => Auth::user()->id
                );

                $idiomas = Idioma::where('estado', 'A')->get();

                foreach ($idiomas as $idioma) {
                    /*
                      if ($idioma->codigo != Config::get('app.locale')) {
                      $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
                      }
                     * 
                     */
                    $imagen->idiomas()->attach($idioma->id, $datos_lang);
                }

                //Mensaje correspondiente a la agregacion exitosa
                $respuesta['mensaje'] = 'Imagen creada.';
                $respuesta['error'] = false;
                $respuesta['data'] = $imagen;
                //return Response::json('success', 200);
            } else {
                //Mensaje correspondiente a la agregacion exitosa
                $respuesta['mensaje'] = 'Imagen errónea.';
                $respuesta['error'] = true;
                $respuesta['data'] = null;
                //return Response::json('error', 400);
            }
        }

        return $respuesta;
    }

    public static function agregarImagenCropped($imagen_portada_crop, $ampliada, $epigrafe_imagen_portada) {

        $respuesta = array();

        $carpeta = "/uploads/";

        $datos_ampliada = array(
            'nombre' => $ampliada,
            //'epigrafe' => $epigrafe_imagen_portada,
            'carpeta' => $carpeta,
            'tipo' => 'G',
            'ampliada' => '',
            'estado' => 'A',
            'fecha_carga' => date("Y-m-d H:i:s"),
            'usuario_id_carga' => Auth::user()->id
        );

        $imagen = static::create($datos_ampliada);

        $datos_lang = array(
            'epigrafe' => $epigrafe_imagen_portada,
            'estado' => 'A',
            'fecha_carga' => date("Y-m-d H:i:s"),
            'usuario_id_carga' => Auth::user()->id
        );

        $idiomas = Idioma::where('estado', 'A')->get();

        foreach ($idiomas as $idioma) {
            /*
              if ($idioma->codigo != Config::get('app.locale')) {
              $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
              }
             * 
             */
            $imagen->idiomas()->attach($idioma->id, $datos_lang);
        }

        $datos_chica = array(
            'nombre' => $imagen_portada_crop,
            //'epigrafe' => $epigrafe_imagen_portada,
            'carpeta' => $carpeta,
            'tipo' => 'C',
            'ampliada' => $imagen->id,
            'estado' => 'A',
            'fecha_carga' => date("Y-m-d H:i:s"),
            'usuario_id_carga' => Auth::user()->id
        );

        $imagen_chica = static::create($datos_chica);

        $datos_lang = array(
            'epigrafe' => $epigrafe_imagen_portada,
            'estado' => 'A',
            'fecha_carga' => date("Y-m-d H:i:s"),
            'usuario_id_carga' => Auth::user()->id
        );

        $idiomas = Idioma::where('estado', 'A')->get();

        foreach ($idiomas as $idioma) {
            /*
              if ($idioma->codigo != Config::get('app.locale')) {
              $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
              }
             * 
             */
            $imagen_chica->idiomas()->attach($idioma->id, $datos_lang);
        }

        //Mensaje correspondiente a la agregacion exitosa
        $respuesta['mensaje'] = 'Imagen creada.';
        $respuesta['error'] = false;
        $respuesta['data'] = $imagen_chica;
        //return Response::json('success', 200);

        return $respuesta;
    }

    public static function uploadImageAngular($imagen_crop, $imagen_ampliada) {

        $count = count($imagen_ampliada->getClientOriginalName()) - 4;

        $filename = Str::limit(Str::slug($imagen_ampliada->getClientOriginalName()), $count, "");
        $extension = $imagen_ampliada->getClientOriginalExtension(); //if you need extension of the file
        //$extension = File::extension($file['name']);

        $carpeta = '/uploads/';
        $directory = public_path() . $carpeta;
        //$filename = sha1(time() . Hash::make($filename) . time()) . ".{$extension}";
        //Pregunto para que no se repita el nombre de la imagen
        if (!is_null(Imagen::imagenPorNombre($filename . ".{$extension}"))) {

            $filename = $filename . "(" . Str::limit(sha1(time()), 3, "") . ")" . ".{$extension}";
        } else {
            $filename = $filename . ".{$extension}";
        }

        Image::make($imagen_ampliada)->resize(null, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($directory . $filename, 60);

        $imagen_crop_name = "small_" . $filename;

        Image::make($imagen_crop)->save($directory . $imagen_crop_name, 80);


        //$imagen_crop->move($directory, $imagen_crop_name);
        $answer = array('answer' => 'File transfer completed', 'imagen_path' => $imagen_crop_name, 'imagen_ampliada' => $filename);

        return $answer;
    }

    public static function uploadImageAngularSlide($imagen_slide) {

        $count = count($imagen_slide->getClientOriginalName()) - 4;

        $filename = Str::limit(Str::slug($imagen_slide->getClientOriginalName()), $count, "");
        $extension = $imagen_slide->getClientOriginalExtension(); //if you need extension of the file
        //$extension = File::extension($file['name']);

        $carpeta = '/uploads/';
        $directory = public_path() . $carpeta;
        //$filename = sha1(time() . Hash::make($filename) . time()) . ".{$extension}";
        //Pregunto para que no se repita el nombre de la imagen
        if (!is_null(Imagen::imagenPorNombre($filename . ".{$extension}"))) {

            $filename = $filename . "(" . Str::limit(sha1(time()), 3, "") . ")" . ".{$extension}";
        } else {
            $filename = $filename . ".{$extension}";
        }

        Image::make($imagen_slide)->resize(570, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($directory . $filename);


        $answer = array('answer' => 'File transfer completed', 'imagen_path' => $filename);

        return $answer;
    }

    public static function agregarImagenAngularSlideHome($imagen, $epigrafe) {
        $respuesta = array();

        $carpeta = "/uploads/";

        $datos = array(
            'nombre' => $imagen,
            //'epigrafe' => $epigrafe,
            'carpeta' => $carpeta,
            'tipo' => 'G',
            'ampliada' => '',
            'estado' => 'A',
            'fecha_carga' => date("Y-m-d H:i:s"),
            'usuario_id_carga' => Auth::user()->id
        );

        $imagen = static::create($datos);

        $datos_lang = array(
            'epigrafe' => $epigrafe,
            'estado' => 'A',
            'fecha_carga' => date("Y-m-d H:i:s"),
            'usuario_id_carga' => Auth::user()->id
        );

        $idiomas = Idioma::where('estado', 'A')->get();

        foreach ($idiomas as $idioma) {
            /*
              if ($idioma->codigo != Config::get('app.locale')) {
              $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
              }
             * 
             */
            $imagen->idiomas()->attach($idioma->id, $datos_lang);
        }

        //Mensaje correspondiente a la agregacion exitosa
        $respuesta['mensaje'] = 'Imagen creada.';
        $respuesta['error'] = false;
        $respuesta['data'] = $imagen;
        //return Response::json('success', 200);

        return $respuesta;
    }

    public static function agregarImagenSlideHome($imagen = null, $epigrafe = null, $coordenadas = null) {

        $respuesta = array();

        $datos = array(
            'imagen' => $imagen,
            'epigrafe' => $epigrafe
        );

        $rules = array(
            'imagen' => array('mimes:jpeg,png,gif'),
        );

        $validator = Validator::make($datos, $rules);

        if ($validator->fails()) {
            //return Response::make($validator->errors->first(), 400);
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = 'no pasa';
        } else {


            $file = $imagen;

            $count = count($file->getClientOriginalName()) - 4;

            $filename = Str::limit(Str::slug($file->getClientOriginalName()), $count, "");
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
            //$extension = File::extension($file['name']);

            $carpeta = '/uploads/';
            $directory = public_path() . $carpeta;
            //$filename = sha1(time() . Hash::make($filename) . time()) . ".{$extension}";
            //Pregunto para que no se repita el nombre de la imagen
            if (!is_null(Imagen::imagenPorNombre($filename . ".{$extension}"))) {

                $filename = $filename . "(" . Str::limit(sha1(time()), 3, "") . ")" . ".{$extension}";
            } else {
                $filename = $filename . ".{$extension}";
            }

            //$upload_success = $file->move($directory, $filename);

            if (Image::make($file)->resize(2000, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($directory . $filename)) {
                $datos = array(
                    'nombre' => $filename,
                    //'epigrafe' => $epigrafe,
                    'carpeta' => $carpeta,
                    'tipo' => 'G',
                    'ampliada' => '',
                    'estado' => 'A',
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'usuario_id_carga' => Auth::user()->id
                );

                $imagen = static::create($datos);

                $datos_lang = array(
                    'epigrafe' => $epigrafe,
                    'estado' => 'A',
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'usuario_id_carga' => Auth::user()->id
                );

                $idiomas = Idioma::where('estado', 'A')->get();

                foreach ($idiomas as $idioma) {
                    /*
                      if ($idioma->codigo != Config::get('app.locale')) {
                      $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
                      }
                     * 
                     */
                    $imagen->idiomas()->attach($idioma->id, $datos_lang);
                }

                //Mensaje correspondiente a la agregacion exitosa
                $respuesta['mensaje'] = 'Imagen creada.';
                $respuesta['error'] = false;
                $respuesta['data'] = $imagen;
                //return Response::json('success', 200);
            } else {
                //Mensaje correspondiente a la agregacion exitosa
                $respuesta['mensaje'] = 'Imagen errónea.';
                $respuesta['error'] = true;
                $respuesta['data'] = null;
                //return Response::json('error', 400);
            }
        }

        return $respuesta;
    }

    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
            'id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $imagen = Imagen::find($input['id']);

            //$imagen->epigrafe = $input['epigrafe'];
            $imagen->fecha_modificacion = date("Y-m-d H:i:s");

            $imagen->save();

            $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

            $imagen_lang = Imagen::join('imagen_lang', 'imagen_lang.imagen_id', '=', 'imagen.id')->where('imagen_lang.lang_id', $lang->id)->where('imagen_lang.estado', 'A')->where('imagen.id', $imagen->id)->first();

            $datos = array(
                'epigrafe' => $input['epigrafe'],
                'fecha_modificacion' => date("Y-m-d H:i:s")
            );

            $imagen_modificacion = DB::table('imagen_lang')->where('id', $imagen_lang->id)->update($datos);

            if ($imagen->ampliada != 0) {
                $datos = array(
                    'id' => $imagen->ampliada,
                    'epigrafe' => $input['epigrafe']
                );
                $imagen = Imagen::editar($datos);
            }

            $respuesta['mensaje'] = 'Imagen modificada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $imagen;
        }

        return $respuesta;
    }

    public static function borrar($input) {
        $respuesta = array();

        $reglas = array(
            'id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $imagen = Imagen::find($input['id']);

            $imagen->fecha_baja = date("Y-m-d H:i:s");
            $imagen->estado = 'B';
            $imagen->usuario_id_baja = Auth::user()->id;

            $imagen->save();

            if ($imagen->ampliada != 0) {
                $datos = array(
                    'id' => $imagen->ampliada
                );
                $imagen = Imagen::borrar($datos);
            }

            $respuesta['mensaje'] = 'Imagen eliminada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $imagen;
        }

        return $respuesta;
    }

    public static function ordenarImagenItem($imagen_id, $orden, $item_id, $destacado) {
        $respuesta = array();

        $datos = array(
            'imagen_id' => $imagen_id,
            'orden' => $orden,
            'item_id' => $item_id
        );

        $reglas = array(
            'imagen_id' => array('integer'),
            'orden' => array('integer'),
            'item_id' => array('integer')
        );

        $validator = Validator::make($datos, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $input = array(
                'item_id' => $item_id,
                'imagen_id' => $imagen_id
            );

            $magen = DB::table('item_imagen')->where(
                            $input)->update(array('orden' => $orden, 'destacado' => $destacado));

            $respuesta['mensaje'] = 'Las imágenes han sido ordenadas.';
            $respuesta['error'] = false;
            $respuesta['data'] = $magen;
        }

        return $respuesta;
    }

    public function miniatura() {
        return DB::table('imagen')
                        ->where('tipo', 'C')
                        ->where('ampliada', $this->id)
                        ->where('estado', 'A')
                        ->first();
    }

    public function ampliada() {
        return Imagen::where('tipo', 'G')
                        ->where('id', $this->ampliada)
                        ->where('estado', 'A')
                        ->first();
    }

    //Me quedo con las categorias a las que pertenece el Item
    public function items() {
        return $this->belongsToMany('Item', 'item_imagen', 'item_id', 'imagen_id');
    }

    //Me quedo con las secciones a las que pertenece el Item
    public function slides() {
        return $this->belongsToMany('Slide', 'slide_imagen', 'slide_id', 'imagen_id');
    }

    public static function imagenPorNombre($nombre) {
        return DB::table('imagen')
                        ->where('nombre', $nombre)
                        ->first();
    }

    public function idiomas() {
        return $this->belongsToMany('Idioma', 'imagen_lang', 'imagen_id', 'lang_id');
    }

    public function lang() {
        $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

        $imagen = Imagen::join('imagen_lang', 'imagen_lang.imagen_id', '=', 'imagen.id')->where('imagen_lang.lang_id', $lang->id)->where('imagen_lang.estado', 'A')->where('imagen.id', $this->id)->first();

        if (is_null($imagen)) {
            echo "Por null";
            $lang = Idioma::where('codigo', 'es')->where('estado', 'A')->first();
            $imagen = Imagen::join('imagen_lang', 'imagen_lang.imagen_id', '=', 'imagen.id')->where('imagen_lang.lang_id', $lang->id)->where('imagen_lang.estado', 'A')->where('imagen.id', $this->id)->first();
        }

        return $imagen;
    }

}
