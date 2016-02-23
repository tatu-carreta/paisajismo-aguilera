<?php

class Etiqueta extends Eloquent {

    //Tabla de la BD
    protected $table = 'etiqueta';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre', 'url', 'fecha_carga', 'usuario_id_carga');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public static function agregar($info) {

        $respuesta = array();

        $rules = array(
                //'archivo' => array('mimes:pdf'),
        );

        $validator = Validator::make($info, $rules);

        if ($validator->fails()) {
            //return Response::make($validator->errors->first(), 400);
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = 'no pasa';
        } else {


            $datos = array(
                'nombre' => $info['etiqueta_nombre'],
                'url' => Str::slug($info['etiqueta_nombre']),
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            $etiqueta = static::create($datos);

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = 'Etiqueta creada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $etiqueta;
        }

        return $respuesta;
    }

//
//    public static function editar($input) {
//        $respuesta = array();
//
//        $reglas = array(
//            'id' => array('integer')
//        );
//
//        $validator = Validator::make($input, $reglas);
//
//        if ($validator->fails()) {
//            $respuesta['mensaje'] = $validator;
//            $respuesta['error'] = true;
//        } else {
//
//            $archivo = Archivo::find($input['id']);
//
//            $archivo->titulo = $input['titulo'];
//            $archivo->fecha_modificacion = date("Y-m-d H:i:s");
//
//            $archivo->save();
//
//            $respuesta['mensaje'] = 'Archivo modificado.';
//            $respuesta['error'] = false;
//            $respuesta['data'] = $archivo;
//        }
//
//        return $respuesta;
//    }

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

            $archivo = Archivo::find($input['id']);

            $archivo->fecha_baja = date("Y-m-d H:i:s");
            $archivo->estado = 'B';
            $archivo->usuario_id_baja = Auth::user()->id;

            $archivo->save();

            $respuesta['mensaje'] = 'Archivo eliminado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $archivo;
        }

        return $respuesta;
    }

    //Me quedo con las categorias a las que pertenece el Item
    public function items() {
        return $this->belongsToMany('Item', 'item_etiqueta', 'item_id', 'etiqueta_id');
    }

}
