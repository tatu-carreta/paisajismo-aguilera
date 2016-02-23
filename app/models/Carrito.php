<?php

class Carrito extends Eloquent {

    //Tabla de la BD
    protected $table = 'carrito';
    //Atributos que van a ser modificables
    protected $fillable = array('usuario_id', 'usuario_address', 'estado', 'fecha_carga');
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
            $respuesta['error'] = 'no pasa';
        } else {

            if (isset(Auth::user()->id)) {
                $datos['usuario_id'] = Auth::user()->id;
            }

            $datos = array(
                //'usuario_id' => $filename,
                'usuario_address' => Request::server('REMOTE_ADDR'),
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
            );

            $carrito = static::create($datos);

            Session::put('carrito', $carrito->id);

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = 'Carrito creado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $carrito;
        }

        return $respuesta;
    }

    public static function agregarProducto($info) {

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

            if (is_null(Session::get('carrito'))) {
                Carrito::agregar($info);
            }

            if (isset($info['cantidad']) && ($info['cantidad'] != "")) {
                $cantidad = $info['cantidad'];
            } else {
                $cantidad = 1;
            }

            $producto = Producto::find($info['producto_id']);

            $carrito = Carrito::find(Session::get('carrito'));

            $productos = array();

            foreach ($carrito->productos as $prod) {
                array_push($productos, $prod->id);
            }

            if (in_array($producto->id, $productos)) {

                $carrito_producto = DB::table('carrito_producto')
                        ->where('carrito_id', $carrito->id)
                        ->where('producto_id', $producto->id)
                        ->select('id', 'cantidad')
                        ->first();

                $datos[$producto->id] = (array(
                    'cantidad' => $carrito_producto->cantidad + $cantidad,
                    //'precio' => $producto->precio(2),
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'estado' => 'A',
                ));

                $carrito->productos()->sync($datos, false);
            } else {
                $datos = array(
                    //'usuario_id' => $filename,
                    //'carrito_id' => $carrito->id,
                    //'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio' => $producto->precio(2),
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'estado' => 'A',
                );

                $carrito->productos()->attach($producto->id, $datos);
            }



            Cart::associate('Producto')->add($producto->id, $producto->item()->lang()->titulo, $cantidad, $producto->precio(2));

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = Lang::get('models.carrito.agregado_presupuesto');
            $respuesta['error'] = false;
            $respuesta['data'] = $carrito;
        }

        return $respuesta;
    }

    public static function editarProducto($input) {
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

            if (isset($input['cantidad']) && ($input['cantidad'] != "")) {
                $cantidad = $input['cantidad'];
            } else {
                $cantidad = 1;
            }

            $producto = Producto::find($input['producto_id']);

            $carrito = Carrito::find(Session::get('carrito'));

            $datos[$producto->id] = (array(
                'cantidad' => $cantidad,
                'precio' => $producto->precio(2),
                'fecha_carga' => date("Y-m-d H:i:s"),
                'estado' => 'A',
            ));

            $carrito->productos()->sync($datos, false);

            Cart::update($input['rowId'], $cantidad);

            $respuesta['mensaje'] = 'Producto carrito modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $carrito;
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

            $respuesta['mensaje'] = Lang::get('models.carrito.quitado_presupuesto');
            $respuesta['error'] = false;
            $respuesta['data'] = $carrito;
        }
        
        return $respuesta;
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
        return $this->belongsToMany('Producto', 'carrito_producto', 'carrito_id', 'producto_id')->where('carrito_producto.estado', 'A');
    }

}
