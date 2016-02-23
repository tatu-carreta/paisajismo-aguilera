<?php

class Pedido extends Eloquent {

    //Tabla de la BD
    protected $table = 'pedido';
    //Atributos que van a ser modificables
    protected $fillable = array('persona_id', 'monto', 'link_compra', 'direccion_id', 'fecha_carga', 'fecha_baja', 'estado');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public static function agregar($info) {

        $respuesta = array();

        $reglas = array(
        );

        $validator = Validator::make($info, $reglas);

        if ($validator->fails()) {
            //return Response::make($validator->errors->first(), 400);
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $datos = array(
                'persona_id' => $info['persona_id'],
                'monto' => 0,
                'fecha_carga' => date("Y-m-d H:i:s"),
                'estado' => 'A',
            );

            $pedido = static::create($datos);

            foreach ($info['productos'] as $producto) {
                $datos = array(
                    'cantidad' => $producto['cantidad'],
                    'precio' => $producto['precio'],
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'estado' => 'A',
                );

                $pedido->productos()->attach($producto['id'], $datos);
            }

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = Lang::get('models.pedido.presupuesto_enviado');
            $respuesta['error'] = false;
            $respuesta['data'] = $pedido;
        }

        return $respuesta;
    }

    public static function borrarProducto($input) {
        $respuesta = array();

        $reglas = array(
            'id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            if (is_null(Session::get('carrito'))) {
                Carrito::agregar($input);
            }

            $producto = Producto::find($input['producto_id']);

            $carrito = Carrito::find(Session::get('carrito'));

            $datos[$producto->id] = (array(
                'fecha_baja' => date("Y-m-d H:i:s"),
                'estado' => 'B',
            ));

            $carrito->productos()->sync($datos, false);

            Cart::remove($input['rowId']);

            $respuesta['mensaje'] = 'Producto carrito borrado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $carrito;
        }
    }

    public static function borrar() {
        $respuesta = array();

        $input = array();

        $reglas = array(
            'id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $carrito = Carrito::find(Session::get('carrito'));

            //$carrito->fecha_baja = date("Y-m-d H:i:s");
            $carrito->estado = 'B';
            //$archivo->usuario_id_baja = Auth::user()->id;

            $carrito->save();

            Cart::destroy();

            Session::forget('carrito');

            $respuesta['mensaje'] = 'Carrito eliminado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $carrito;
        }

        return $respuesta;
    }

    public function productos() {
        return $this->belongsToMany('Producto', 'pedido_producto', 'pedido_id', 'producto_id')->where('pedido_producto.estado', 'A');
    }

    public function persona() {
        return $this->hasOne('Persona', 'id', 'persona_id');
    }

}
