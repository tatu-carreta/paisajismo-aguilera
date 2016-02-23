<?php

class Cliente extends Eloquent {

    //Tabla de la BD
    protected $table = 'cliente';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre_apellido', 'email', 'telefono', 'consulta', 'fecha_carga');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public static function agregar($input) {
        $respuesta = array();

        //Se definen las reglas con las que se van a validar los datos..
        $reglas = array(
            //'nombre_apellido' => array('max:200'),
            'email' => array('required'),
        );


        //Se realiza la validación
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = Lang::get('models.cliente.email_invalido');

            //Si está todo mal, carga lo que corresponde en el mensaje.

            $respuesta['error'] = true;
        } else {

            //Se cargan los datos necesarios para la creacion del Item
            $datos = array(
                'email' => $input['email'],
                'fecha_carga' => date("Y-m-d H:i:s"),
            );

            if (isset($input['nombre_apellido'])) {
                $datos['nombre_apellido'] = $input['nombre_apellido'];
            }
            if (isset($input['telefono'])) {
                $datos['telefono'] = $input['telefono'];
            }
            if (isset($input['consulta'])) {
                $datos['consulta'] = $input['consulta'];
            }

            //Lo crea definitivamente
            $cliente = static::create($datos);

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = Lang::get('models.cliente.creado');
            $respuesta['error'] = false;
            $respuesta['data'] = $cliente;
        }

        return $respuesta;
    }

    /*
      public static function editar($input) {

      $respuesta = array();

      $reglas = array(
      'email' => array('required'),
      );

      $validator = Validator::make($input, $reglas);

      if ($validator->fails()) {
      $respuesta['mensaje'] = "El nombre de la marca ya existe o supera los 30 caracteres.";
      $respuesta['error'] = true;
      } else {

      $cliente = Cliente::find($input['id']);

      $marca->nombre = $input['nombre'];
      $marca->tipo = $input['tipo'];
      $marca->fecha_modificacion = date("Y-m-d H:i:s");

      $marca->save();

      if (isset($input['file']) && ($input['file'] != "")) {

      $imagen_creada = Imagen::agregarImagen($input['file']);

      $marca->imagen_id = $imagen_creada['data']->miniatura()->id;
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
     */
}
