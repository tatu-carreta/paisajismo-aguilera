<?php

class Noticia extends Texto {

    //Tabla de la BD
    protected $table = 'noticia';
    //Atributos que van a ser modificables
    protected $fillable = array('texto_id', 'fecha', 'fuente');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregar($input) {

        $respuesta = array();

        $reglas = array(
            'titulo' => array('required', 'max:100'),
            'fecha' => array('required', 'date_format:"d/m/Y"')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            if ($validator->messages()->first('titulo') != "") {
                $respuesta['mensaje'] = $validator->messages()->first('titulo');
            } elseif ($validator->messages()->first('fecha') != "") {
                $respuesta['mensaje'] = $validator->messages()->first('fecha');
            } else {
                $respuesta['mensaje'] = "Hubo un error al agregar la noticia. Vuelva a intentarlo en unos minutos.";
            }
            $respuesta['error'] = true;
        } else {
            //Lo crea definitivamente
            $texto = Texto::agregar($input);

            if (isset($input['fecha'])) {

                $fec_noticia = new DateTime(str_replace('/', '-', $input['fecha']));
                $fecha = $fec_noticia->format('Y-m-d');
                //$fecha = date('Y-m-d', strtotime($input['fecha']));
                //$fecha = $input['fecha'];
            } else {
                $fecha = NULL;
            }

            if (isset($input['fuente'])) {

                $fuente = $input['fuente'];
            } else {
                $fuente = NULL;
            }

            if (!$texto['error']) {

                $noticia = static::create(['texto_id' => $texto['data']->id, 'fecha' => $fecha, 'fuente' => $fuente]);

                $respuesta['data'] = $noticia;
                $respuesta['error'] = false;
                $respuesta['mensaje'] = "Noticia creada.";
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = $texto['mensaje'];
            }
        }

        return $respuesta;
    }

    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
            'titulo' => array('required', 'max:100', 'unique:item,titulo,' . $input['id']),
            'fecha' => array('required', 'date_format:"d/m/Y"')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            if ($validator->messages()->first('titulo') != "") {
                $respuesta['mensaje'] = $validator->messages()->first('titulo');
            } elseif ($validator->messages()->first('fecha') != "") {
                $respuesta['mensaje'] = $validator->messages()->first('fecha');
            } else {
                $respuesta['mensaje'] = "Hubo un error al editar la noticia. Vuelva a intentarlo en unos minutos.";
            }
            $respuesta['error'] = true;
        } else {

            $noticia = Noticia::find($input['noticia_id']);

            if (isset($input['fecha'])) {

                $fec_noticia = new DateTime(str_replace('/', '-', $input['fecha']));

                $fecha = $fec_noticia->format('Y-m-d');
                //$fecha = date('Y-m-d', strtotime($input['fecha']));
                //$fecha = $input['fecha'];
            } else {
                $fecha = NULL;
            }
            if (isset($input['fuente'])) {

                $fuente = $input['fuente'];
            } else {
                $fuente = NULL;
            }

            $noticia->fecha = $fecha;
            $noticia->fuente = $fuente;

            $noticia->save();

            $input['texto_id'] = $noticia->texto_id;

            $texto = Texto::editar($input);

            $respuesta['mensaje'] = 'Noticia modificada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $noticia;
        }

        return $respuesta;
    }

    public static function borrar($input) {
        $respuesta = array();

        $reglas = array(
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $item = Item::find($input['id']);

            $item->fecha_baja = date("Y-m-d H:i:s");
            $item->url = $item->url . "-borrado";
            $item->estado = 'B';
            $item->usuario_id_baja = Auth::user()->id;

            $item->save();

            $respuesta['mensaje'] = 'Noticia eliminada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $item;
        }

        return $respuesta;
    }

    public static function borrarItemSeccion($input) {
        $respuesta = array();

        $reglas = array(
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $baja_item_seccion = DB::table('item_seccion')->where($input)->update(array('estado' => 'B'));

            $respuesta['mensaje'] = 'Noticia eliminada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $baja_item_seccion;
        }

        return $respuesta;
    }

    public static function destacar($input) {
        $respuesta = array();

        $reglas = array();

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $noticia = Noticia::find($input['noticia_id']);

            $data = array(
                'item_id' => $noticia->texto()->item()->id,
                'seccion_id' => $noticia->texto()->item()->seccionItem()->id
            );

            $item = Item::destacar($data);

            $respuesta['mensaje'] = 'Noticia destacada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $noticia;
        }

        return $respuesta;
    }

    public function texto() {
        return Texto::find($this->texto_id);
    }

}
