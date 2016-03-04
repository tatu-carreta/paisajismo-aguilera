<?php

class Slide extends Eloquent {

    //Tabla de la BD
    protected $table = 'slide';
    //Atributos que van a ser modificables
    protected $fillable = array('seccion_id', 'tipo', 'estado', 'fecha_carga', 'fecha_modificacion', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Slide
    public static function agregarSlide($input) {

        $respuesta = array();

        //Se definen las reglas con las que se van a validar los datos..
        $reglas = array(
            'seccion_id' => array('required'),
            'tipo' => array('required')
        );

        //Se realiza la validación
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            //Se cargan los datos necesarios para la creacion del Item
            $datos = array(
                'seccion_id' => $input['seccion_id'],
                'tipo' => $input['tipo'],
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            //Lo crea definitivamente
            $slide = static::create($datos);

            if (isset($input['file']) && ($input['file'] != "")) {
                if (is_array($input['file'])) {
                    foreach ($input['file'] as $key => $imagen) {
                        if ($imagen != "") {

                            if (isset($input['epigrafe'][$key]) && ($input['epigrafe'][$key] != "")) {
                                $ep_im = $input['epigrafe'][$key];
                            } else {
                                $ep_im = "";
                            }

                            if ($input['tipo'] == "I") {
                                $imagen_creada = Imagen::agregarImagenSlideHome($imagen, $ep_im);
                            } else {
                                $imagen_creada = Imagen::agregarImagenAngularSlideHome($imagen, $ep_im);
                            }

                            if (!$imagen_creada['error']) {

                                $info = array(
                                    'estado' => 'A',
                                    'fecha_carga' => date("Y-m-d H:i:s"),
                                    'usuario_id_carga' => Auth::user()->id
                                );

                                $slide->imagenes()->attach($imagen_creada['data']->id, $info);
                            }
                        }
                    }
                }
            }

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = 'Slide creado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $slide;
        }

        return $respuesta;
    }

    public static function agregarSlideHome($input) {

        $respuesta = array();

        //Se definen las reglas con las que se van a validar los datos..
        $reglas = array(
            'seccion_id' => array('required'),
            'tipo' => array('required')
        );

        //Se realiza la validación
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            //Se cargan los datos necesarios para la creacion del Item
            $datos = array(
                'seccion_id' => $input['seccion_id'],
                'tipo' => $input['tipo'],
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            //Lo crea definitivamente
            $slide = static::create($datos);

            if (isset($input['imagenes_slide']) && ($input['imagenes_slide'] != "")) {
                if (is_array($input['imagenes_slide'])) {
                    foreach ($input['imagenes_slide'] as $key => $imagen) {
                        if ($imagen != "") {
                            
                            if (isset($input['epigrafe'][$key]) && ($input['epigrafe'][$key] != "")) {
                                $ep_im = $input['epigrafe'][$key];
                            } else {
                                $ep_im = "";
                            }
                            
                            if ($input['tipo'] == "I") {
                                $imagen_creada = Imagen::agregarImagenAngularSlideHome($imagen, $ep_im);
                            } else {
                                $imagen_creada = Imagen::agregarImagenAngularSlideHome($imagen, $ep_im);
                            }

                            if (!$imagen_creada['error']) {

                                $info = array(
                                    'estado' => 'A',
                                    'fecha_carga' => date("Y-m-d H:i:s"),
                                    'usuario_id_carga' => Auth::user()->id
                                );

                                $slide->imagenes()->attach($imagen_creada['data']->id, $info);
                            }
                        }
                    }
                }
            }

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = 'Slide creado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $slide;
        }

        return $respuesta;
    }

    public static function editarSlideHome($input) {

        $respuesta = array();

        //Se definen las reglas con las que se van a validar los datos..
        $reglas = array(
            'slide_id' => array('required'),
        );

        //Se realiza la validación
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            //Se cargan los datos necesarios para la creacion del Item
            $slide = Slide::find($input['slide_id']);

            $slide->fecha_modificacion = date("Y-m-d H:i:s");

            $slide->save();

            //Lo crea definitivamente
            if (isset($input['imagenes_slide']) && ($input['imagenes_slide'] != "")) {
                if (is_array($input['imagenes_slide'])) {
                    foreach ($input['imagenes_slide'] as $key => $imagen) {
                        if ($imagen != "") {
                            
                            if (isset($input['epigrafe'][$key]) && ($input['epigrafe'][$key] != "")) {
                                $ep_im = $input['epigrafe'][$key];
                            } else {
                                $ep_im = "";
                            }
                            
                            $imagen_creada = Imagen::agregarImagenAngularSlideHome($imagen, $ep_im);

                            if (!$imagen_creada['error']) {

                                $info = array(
                                    'estado' => 'A',
                                    'fecha_carga' => date("Y-m-d H:i:s"),
                                    'usuario_id_carga' => Auth::user()->id
                                );

                                $slide->imagenes()->attach($imagen_creada['data']->id, $info);
                            }
                        }
                    }
                }
            }

            if (isset($input['imagen_slide_editar']) && ($input['imagen_slide_editar'] != "")) {
                if (is_array($input['imagen_slide_editar'])) {
                    foreach ($input['imagen_slide_editar'] as $key => $imagen) {
                        if ($imagen != "") {

                            if (isset($input['epigrafe_imagen_slide_editar'][$key]) && ($input['epigrafe_imagen_slide_editar'][$key] != "")) {
                                $ep_im = $input['epigrafe_imagen_slide_editar'][$key];
                            } else {
                                $ep_im = "";
                            }
                            
                            $info = array(
                                'id' => $imagen,
                                'epigrafe' => $ep_im
                            );

                            $imagen_creada = Imagen::editar($info);
                        }
                    }
                }
            }

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = 'Slide modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $slide;
        }

        return $respuesta;
    }

    //Me quedo con las categorias a las que pertenece el Item
    public function imagenes() {
        return $this->belongsToMany('Imagen', 'slide_imagen', 'slide_id', 'imagen_id')->where('slide_imagen.estado', 'A')->where('imagen.estado', 'A');
    }

    //Me quedo con las secciones a las que pertenece el Item
    public function seccion() {
        return $this->belongsTo('Seccion', 'seccion_id');
    }

}
