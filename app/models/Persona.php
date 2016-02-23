<?php

class Persona extends Eloquent {

    //Tabla de la BD
    protected $table = 'persona';
    //Atributos que van a ser modificables
    protected $fillable = array('apellido', 'nombre', 'email', 'fecha_nacimiento', 'direccion_id', 'fecha_carga', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja', 'estado');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public static function agregar($input) {

        $respuesta = array();

        $reglas = array(
            'email' => array('required', 'email'),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator->messages()->first('email');
            $respuesta['error'] = true;
        } else {

            $datos = array(
                'email' => $input['email'],
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => '1'
            );

            if (isset($input['apellido']) && ($input['apellido'] != "")) {
                $datos['apellido'] = $input['apellido'];
            }
            if (isset($input['nombre']) && ($input['nombre'] != "")) {
                $datos['nombre'] = $input['nombre'];
            }
            if (isset($input['fecha_nacimiento']) && ($input['fecha_nacimiento'] != "")) {
                $datos['fecha_nacimiento'] = $input['fecha_nacimiento'];
            }
            if (isset($input['calle']) && ($input['calle'] != "")) {

                $direccion = Direccion::agregar($input);

                if (!$direccion['error']) {
                    $datos['direccion_id'] = $direccion['data']->id;
                }
            }


            $persona = static::create($datos);

            if (isset($input['telefono']) && ($input['telefono'] != "")) {

                $telefono = Telefono::agregar($input);

                if (!$telefono['error']) {
                    $info = array(
                        'estado' => 'A',
                    );

                    $persona->telefonos()->attach($telefono['data'], $info);
                }
            }

            $respuesta['mensaje'] = 'Persona creada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $persona;
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

    public function direccion() {
        return $this->hasOne('Direccion', 'id', 'direccion_id');
    }

    public function telefonos() {
        return $this->belongsToMany('Telefono', 'persona_telefono')->where('persona_telefono.estado', 'A')->where('telefono.estado', 'A');
    }

    public function pedidos() {
        return $this->hasMany('Pedido')->where('pedido.estado', 'A');
    }

}
