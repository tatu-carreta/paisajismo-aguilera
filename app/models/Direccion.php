<?php

class Direccion extends Eloquent {

    //Tabla de la BD
    protected $table = 'direccion';
    //Atributos que van a ser modificables
    protected $fillable = array('calle', 'numero', 'piso', 'departamento', 'ciudad_id', 'latitud', 'longitud', 'fecha_carga', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja', 'estado');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public static function agregar($input) {

        $respuesta = array();

        $reglas = array(
            'calle' => array('required'),
            'numero' => array('required'),
            'ciudad_id' => array('required'),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $datos = array(
                'calle' => $input['calle'],
                'numero' => $input['numero'],
                'ciudad_id' => $input['ciudad_id'],
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            if (isset($input['piso']) && ($input['piso'] != "")) {
                $datos['piso'] = $input['piso'];
            }
            if (isset($input['departamento']) && ($input['departamento'] != "")) {
                $datos['departamento'] = $input['departamento'];
            }
            if (isset($input['latitud']) && ($input['latitud'] != "")) {
                $datos['latitud'] = $input['latitud'];
            }
            if (isset($input['longitud']) && ($input['longitud'] != "")) {
                $datos['longitud'] = $input['longitud'];
            }

            $direccion = static::create($datos);

            $respuesta['mensaje'] = 'Dirección creada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $direccion;
        }

        return $respuesta;
    }
/*
    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
            'titulo' => array('max:50'),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $seccion = Seccion::find($input['id']);

            $seccion->titulo = $input['titulo'];
            $seccion->fecha_modificacion = date("Y-m-d H:i:s");

            $seccion->save();

            $respuesta['mensaje'] = 'Sección modificada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $seccion;
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

            $seccion = Seccion::find($input['id']);

            $seccion->fecha_baja = date("Y-m-d H:i:s");
            $seccion->estado = 'B';
            $seccion->usuario_id_baja = Auth::user()->id;

            $seccion->save();

            $respuesta['mensaje'] = 'Sección eliminada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $seccion;
        }

        return $respuesta;
    }
 * 
 */

    public function ciudad() {
        return $this->belongsTo('Ciudad');
    }
    
    public function persona() {
        return $this->belongsTo('Persona');
    }

}
