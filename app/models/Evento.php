<?php

class Evento extends Texto {

    //Tabla de la BD
    protected $table = 'evento';
    //Atributos que van a ser modificables
    protected $fillable = array('texto_id', 'fecha_desde', 'fecha_hasta');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregar($input) {


        //Lo crea definitivamente
        $texto = Texto::agregar($input);

        if (isset($input['fecha_desde'])) {

            $fecha_desde = $input['fecha_desde'];
        } else {
            $fecha_desde = NULL;
        }

        if (isset($input['fecha_hasta'])) {

            $fecha_hasta = $input['fecha_hasta'];
        } else {
            $fecha_hasta = NULL;
        }

        if (!$texto['error']) {

            $evento = static::create(['texto_id' => $texto['data']->id, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta]);

            $respuesta['data'] = $evento;
            $respuesta['error'] = false;
            $respuesta['mensaje'] = "Evento creado.";
        } else {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = "El evento no pudo ser creado. Compruebe los campos.";
        }


        return $respuesta;
    }

    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
            'titulo' => array('required', 'max:50', 'unique:item,titulo,' . $input['id']),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator->messages()->first('titulo');
            $respuesta['error'] = true;
        } else {

            $evento = Evento::find($input['evento_id']);

            if (isset($input['fecha_desde'])) {

                $fecha_desde = $input['fecha_desde'];
            } else {
                $fecha_desde = NULL;
            }

            if (isset($input['fecha_hasta'])) {

                $fecha_hasta = $input['fecha_hasta'];
            } else {
                $fecha_hasta = NULL;
            }

            $evento->fecha_desde = $fecha_desde;
            $evento->fecha_hasta = $fecha_hasta;

            $evento->save();

            $input['texto_id'] = $evento->texto_id;

            $texto = Texto::editar($input);

            $respuesta['mensaje'] = 'Evento modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $evento;
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

            $respuesta['mensaje'] = 'Evento eliminado.';
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

            $respuesta['mensaje'] = 'Evento eliminado.';
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

            $evento = Evento::find($input['evento_id']);

            $data = array(
                'item_id' => $evento->texto()->item()->id,
                'seccion_id' => $evento->texto()->item()->seccionItem()->id
            );

            $item = Item::destacar($data);

            $respuesta['mensaje'] = 'Evento destacado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $evento;
        }

        return $respuesta;
    }

    public function texto() {
        return Texto::find($this->texto_id);
    }

}
