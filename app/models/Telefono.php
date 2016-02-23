<?php

class Telefono extends Eloquent {

    //Tabla de la BD
    protected $table = 'telefono';
    //Atributos que van a ser modificables
    protected $fillable = array('tipo_telefono_id', 'caracteristica', 'numero', 'fecha_carga', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja', 'estado');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public static function agregar($input) {

        $respuesta = array();

        $reglas = array(
            'tipo_telefono_id' => array('required'),
            'telefono' => array('required'),
        );
        
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {
            
            $datos = array(
                'tipo_telefono_id' => $input['tipo_telefono_id'],
                //'caracteristica' => $input['caracteristica'],
                'numero' => $input['telefono'],
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => 1//Auth::user()->id
            );
            
            $telefono = static::create($datos);

            $respuesta['mensaje'] = 'Teléfono creado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $telefono;
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

    public function tipo_telefono() {
        return $this->belongsTo('Telefono');
    }

    public function persona() {
        return $this->belongsToMany('Persona', 'persona_telefono', 'persona_id', 'telefono_id');
    }

}
