<?php

class Marca extends Eloquent {

    //Tabla de la BD
    protected $table = 'marca';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre', 'imagen_id', 'tipo', 'estado', 'fecha_carga', 'fecha_modificacion', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregar($input) {
        $respuesta = array();

        //Se definen las reglas con las que se van a validar los datos..
        $reglas = array(
            'nombre' => array('max:50', 'unique:marca'),
        );


        //Se realiza la validación
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = "El nombre de la marca ya existe o supera los 50 caracteres.";

            //Si está todo mal, carga lo que corresponde en el mensaje.

            $respuesta['error'] = true;
        } else {

            //Se cargan los datos necesarios para la creacion del Item
            $datos = array(
                'nombre' => $input['nombre'],
                'tipo' => $input['tipo'],
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            //Lo crea definitivamente
            $marca = static::create($datos);

            //if (isset($input['file']) && ($input['file'] != "")) {
            if (isset($input['imagen_portada_crop']) && ($input['imagen_portada_crop'] != "")) {

                //$imagen_creada = Imagen::agregarImagen($input['file']);
                $imagen_creada = Imagen::agregarImagenCropped($input['imagen_portada_crop'], $input['imagen_portada_crop'], null);

                //$marca->imagen_id = $imagen_creada['data']->miniatura()->id;
                $marca->imagen_id = $imagen_creada['data']->id;
                $marca->save();
            }

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = 'Marca creada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $marca;
        }

        return $respuesta;
    }

    public static function editar($input) {

        $respuesta = array();

        $reglas = array(
            'nombre' => array('max:50', 'unique:marca,nombre,' . $input['id']),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = "El nombre de la marca ya existe o supera los 50 caracteres.";
            $respuesta['error'] = true;
        } else {

            $marca = Marca::find($input['id']);

            $marca->nombre = $input['nombre'];
            $marca->tipo = $input['tipo'];
            $marca->fecha_modificacion = date("Y-m-d H:i:s");

            $marca->save();

            //if (isset($input['file']) && ($input['file'] != "")) {
            if (isset($input['imagen_portada_crop']) && ($input['imagen_portada_crop'] != "")) {

                //$imagen_creada = Imagen::agregarImagen($input['file']);
                $imagen_creada = Imagen::agregarImagenCropped($input['imagen_portada_crop'], $input['imagen_portada_crop'], null);

                //$marca->imagen_id = $imagen_creada['data']->miniatura()->id;
                $marca->imagen_id = $imagen_creada['data']->id;
                $marca->save();
            }

            $respuesta['mensaje'] = 'Marca modificada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $marca;
        }

        return $respuesta;
    }

    public static function borrar($input) {
        $respuesta = array();

        $reglas = array('id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta ['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $marca = Marca::find($input['id']);

            $marca->fecha_baja = date("Y-m-d H:i:s");
            $marca->nombre = $marca->nombre . "-borrado";
            $marca->estado = 'B';
            $marca->usuario_id_baja = Auth::user()->id;

            $marca->save();

            $respuesta['mensaje'] = 'Marca eliminada';
            $respuesta['error'] = false;
            $respuesta['data'] = $marca;
        }

        return $respuesta;
    }

    public static function quitarImagen($input) {
        $respuesta = array();

        $reglas = array('id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta ['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $marca = Marca::find($input['id']);

            $marca->fecha_modificacion = date("Y-m-d H:i:s");
            $marca->imagen_id = 'NULL';

            $marca->save();

            $respuesta['mensaje'] = 'La imagen de la marca fue eliminada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $marca;
        }

        return $respuesta;
    }

    public function imagen() {
        return Imagen::find($this->imagen_id);
    }

}
