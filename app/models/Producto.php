<?php

class Producto extends Item {

    //Tabla de la BD
    protected $table = 'producto';
    //Atributos que van a ser modificables
    protected $fillable = array('item_id');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregarProducto($input) {

        $respuesta = array();

        //Se definen las reglas con las que se van a validar los datos
        //del PRODUCTO
        $reglas = array(
            'titulo' => array('required', 'max:9', 'unique:item_lang'),
            'seccion_id' => array('integer'),
            'imagen_portada_crop' => array('required'),
        );

        //Se realiza la validación
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {

            $messages = $validator->messages();
            if ($messages->has('titulo')) {
                $respuesta['mensaje'] = 'El código del producto contiene más de 9 caracteres o ya existe.';
            } elseif ($messages->has('imagen_portada_crop')) {
                $respuesta['mensaje'] = 'Se olvidó de guardar la imagen recortada.';
            } else {
                $respuesta['mensaje'] = 'Los datos necesarios para el producto son erróneos.';
            }

            //Si está todo mal, carga lo que corresponde en el mensaje.

            $respuesta['error'] = true;
        } else {


            //Lo crea definitivamente
            $item = Item::agregarItem($input);

            if (isset($input['cuerpo'])) {

                $cuerpo = $input['cuerpo'];
            } else {
                $cuerpo = NULL;
            }

            if (!$item['error']) {

                $producto = static::create(['item_id' => $item['data']->id]);

                $datos_lang = array(
                    'cuerpo' => $cuerpo,
                );

                $idiomas = Idioma::where('estado', 'A')->get();

                foreach ($idiomas as $idioma) {
                    /*
                      if ($idioma->codigo != Config::get('app.locale')) {
                      $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
                      }
                     * 
                     */
                    $producto->idiomas()->attach($idioma->id, $datos_lang);
                }

                if ($producto) {
                    if (isset($input['precio']) && ($input['precio'] != "")) {
                        $valores = array(
                            "valor" => $input['precio'],
                            "estado" => "A"
                        );
                        $producto->precios()->attach(2, $valores);
                    }

                    if (isset($input['item_destacado']) && ($input['item_destacado'] == 'O')) {
                        if (isset($input['precio_antes']) && ($input['precio_antes'] != "")) {

                            $datos = array(
                                "producto_id" => $producto->id,
                                "tipo_precio_id" => 1,
                            );
                            $baja_producto_precio = DB::table('producto_precio')->where($datos)->update(array('estado' => 'B'));

                            $valores = array(
                                "valor" => $input['precio_antes'],
                                "estado" => "A"
                            );
                            $producto->precios()->attach(1, $valores);
                        }
                        if (isset($input['precio_actual']) && ($input['precio_actual'] != "")) {

                            $datos = array(
                                "producto_id" => $producto->id,
                                "tipo_precio_id" => 2,
                            );
                            $baja_producto_precio = DB::table('producto_precio')->where($datos)->update(array('estado' => 'B'));

                            $valores = array(
                                "valor" => $input['precio_actual'],
                                "estado" => "A"
                            );
                            $producto->precios()->attach(2, $valores);
                        }
                    }

                    if (isset($input['marca_principal']) && ($input['marca_principal'] != "")) {
                        $valores = array(
                            "producto_id" => $producto->id,
                            "marca_id" => $input['marca_principal'],
                            "estado" => "A"
                        );
                        DB::table('producto_marca')->insert($valores);
                        //$producto->marcas_principales()->attach(1, $valores);
                    }
                    if (isset($input['marcas_secundarias']) && ($input['marcas_secundarias'] != "")) {
                        foreach ($input['marcas_secundarias'] as $key => $marca) {
                            $valores = array(
                                "producto_id" => $producto->id,
                                "marca_id" => $marca,
                                "estado" => "A"
                            );
                            DB::table('producto_marca')->insert($valores);
                            //$producto->marcas_principales()->attach(1, $valores);
                        }
                    }

                    $respuesta['data'] = $producto;
                    $respuesta['error'] = false;
                    $respuesta['mensaje'] = "Producto publicado.";
                } else {
                    $respuesta['error'] = true;
                    $respuesta['mensaje'] = "El producto no pudo ser creado. Compruebe los campos.";
                }
            } else {
                $respuesta = $item;
            }
        }
        return $respuesta;
    }

    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
            'titulo' => array('required', 'max:9', 'unique:item,titulo,' . $input['id']),
        );

        if (isset($input['imagen_portada_crop'])) {
            $reglas['imagen_portada_crop'] = array('required');
        }

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $messages = $validator->messages();
            if ($messages->has('titulo')) {
                $respuesta['mensaje'] = $messages->first('titulo');
            } elseif ($messages->has('imagen_portada_crop')) {
                $respuesta['mensaje'] = 'Se olvidó de guardar la imagen recortada.';
            } else {
                $respuesta['mensaje'] = 'Los datos necesarios para el producto son erróneos.';
            }
            $respuesta['error'] = true;
        } else {

            $producto = Producto::find($input['producto_id']);

            if (isset($input['cuerpo'])) {

                $cuerpo = $input['cuerpo'];
            } else {
                $cuerpo = NULL;
            }

//            $producto->cuerpo = $cuerpo;
//
//            $producto->save();

            $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

            $producto_lang = Producto::join('producto_lang', 'producto_lang.producto_id', '=', 'producto.id')->where('producto_lang.lang_id', $lang->id)->where('producto.id', $producto->id)->first();

            $datos = array(
                'cuerpo' => $cuerpo,
            );

            $producto_modificacion = DB::table('producto_lang')->where('id', $producto_lang->id)->update($datos);

            if (isset($input['precio']) && ($input['precio'] != "")) {

                $datos = array(
                    "producto_id" => $input['producto_id'],
                    "tipo_precio_id" => 2,
                );
                $baja_producto_precio = DB::table('producto_precio')->where($datos)->update(array('estado' => 'B'));

                $valores = array(
                    "valor" => $input['precio'],
                    "estado" => "A"
                );
                $producto->precios()->attach(2, $valores);
            }


            if (isset($input['item_destacado']) && ($input['item_destacado'] == 'O')) {
                if (isset($input['precio_antes']) && ($input['precio_antes'] != "")) {

                    $datos = array(
                        "producto_id" => $producto->id,
                        "tipo_precio_id" => 1,
                    );
                    $baja_producto_precio = DB::table('producto_precio')->where($datos)->update(array('estado' => 'B'));

                    $valores = array(
                        "valor" => $input['precio_antes'],
                        "estado" => "A"
                    );
                    $producto->precios()->attach(1, $valores);
                }
                if (isset($input['precio_actual']) && ($input['precio_actual'] != "")) {

                    $datos = array(
                        "producto_id" => $producto->id,
                        "tipo_precio_id" => 2,
                    );
                    $baja_producto_precio = DB::table('producto_precio')->where($datos)->update(array('estado' => 'B'));

                    $valores = array(
                        "valor" => $input['precio_actual'],
                        "estado" => "A"
                    );
                    $producto->precios()->attach(2, $valores);
                }
            }


            if (isset($input['marca_principal']) && ($input['marca_principal'] != "")) {
                $datos = array(
                    "producto_id" => $input['producto_id'],
                    "marca_id" => $input['marca_principal']
                );

                $valores = array(
                    "producto_id" => $producto->id,
                    "marca_id" => $input['marca_principal'],
                    "estado" => "A"
                );

                $baja_producto_marca = DB::table('producto_marca')->where($datos)->update(array('estado' => 'B'));

                DB::table('producto_marca')->insert($valores);
                //$producto->marcas_principales()->attach(1, $valores);
            }
            if (isset($input['marcas_secundarias']) && ($input['marcas_secundarias'] != "")) {
                foreach ($producto->marcas_secundarias() as $marca_secundaria) {
                    $datos = array(
                        "producto_id" => $producto->id,
                        "marca_id" => $marca_secundaria->id
                    );
                    $baja_producto_marca = DB::table('producto_marca')->where($datos)->update(array('estado' => 'B'));
                }
                foreach ($input['marcas_secundarias'] as $key => $marca) {
                    $valores = array(
                        "producto_id" => $producto->id,
                        "marca_id" => $marca,
                        "estado" => "A"
                    );
                    DB::table('producto_marca')->insert($valores);
                    //$producto->marcas_principales()->attach(1, $valores);
                }
            }

            $item = Item::editarItem($input);

            $respuesta['mensaje'] = 'Producto modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $producto;
        }

        return $respuesta;
    }

    public static function borrarItem($input) {
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

            $respuesta['mensaje'] = 'Producto eliminado.';
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

            $respuesta['mensaje'] = 'Producto eliminado.';
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

            $producto = Producto::find($input['producto_id']);

            if (isset($input['precio']) && ($input['precio'] != "")) {

                $datos = array(
                    "producto_id" => $input['producto_id'],
                    "tipo_precio_id" => 2,
                );
                $baja_producto_precio = DB::table('producto_precio')->where($datos)->update(array('estado' => 'B'));

                $valores = array(
                    "valor" => $input['precio'],
                    "estado" => "A"
                );
                $producto->precios()->attach(2, $valores);
            }

            $data = array(
                'item_id' => $producto->item()->id,
                'seccion_id' => $producto->item()->seccionItem()->id
            );

            $item = Item::destacar($data);

            $respuesta['mensaje'] = 'Producto destacado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $producto;
        }

        return $respuesta;
    }

    public static function ponerNuevo($input) {
        $respuesta = array();

        $reglas = array();

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $item = Item::ponerNuevo($input);

            $respuesta['mensaje'] = 'Producto nuevo.';
            $respuesta['error'] = false;
            $respuesta['data'] = $item;
        }

        return $respuesta;
    }

    public static function ponerOferta($input) {
        $respuesta = array();

        $reglas = array();

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $producto = Producto::find($input['producto_id']);

            if (isset($input['precio_antes']) && ($input['precio_antes'] != "")) {

                $datos = array(
                    "producto_id" => $input['producto_id'],
                    "tipo_precio_id" => 1,
                );
                $baja_producto_precio = DB::table('producto_precio')->where($datos)->update(array('estado' => 'B'));

                $valores = array(
                    "valor" => $input['precio_antes'],
                    "estado" => "A"
                );
                $producto->precios()->attach(1, $valores);
            }
            if (isset($input['precio_actual']) && ($input['precio_actual'] != "")) {

                $datos = array(
                    "producto_id" => $input['producto_id'],
                    "tipo_precio_id" => 2,
                );
                $baja_producto_precio = DB::table('producto_precio')->where($datos)->update(array('estado' => 'B'));

                $valores = array(
                    "valor" => $input['precio_actual'],
                    "estado" => "A"
                );
                $producto->precios()->attach(2, $valores);
            }

            $data = array(
                'item_id' => $producto->item()->id,
                'seccion_id' => $input['seccion_id']
            );

            $item = Item::ponerOferta($data);

            $respuesta['mensaje'] = 'Producto destacado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $producto;
        }

        return $respuesta;
    }

    //Me quedo con los precios del Producto
    public function precios() {
        return $this->belongsToMany('TipoPrecio', 'producto_precio', 'producto_id', 'tipo_precio_id')->where('producto_precio.estado', 'A')->select('tipo_precio.nombre', 'tipo_precio.id', 'producto_precio.valor');
    }

    public function precio($tipo) {
        if ($data = DB::table('producto_precio')
                ->where('estado', 'A')
                ->where('producto_id', $this->id)
                ->where('tipo_precio_id', $tipo)
                ->first()) {
            return $data->valor;
        }
        return 0;
        //return $this->belongsToMany('TipoPrecio', 'producto_precio', 'producto_id', 'tipo_precio_id')->where('producto_precio.estado', 'A')->where('producto_precio.tipo_precio_id', $tipo)->select('tipo_precio.nombre', 'tipo_precio.id', 'producto_precio.valor');
    }

    public function marca_principal() {
        $marca = NULL;


        foreach ($this->marcas_principales() as $marca_principal) {
            $marca = Marca::find($marca_principal->id);
        }

        return $marca;
    }

    public function marcas_secundarias_editar() {
        $marcas = array();


        foreach ($this->marcas_secundarias() as $marca_secundaria) {
            array_push($marcas, $marca_secundaria->id);
        }

        return $marcas;
    }

    public function marcas_principales() {
        return $this->belongsToMany('Marca', 'producto_marca', 'producto_id', 'marca_id')->where('producto_marca.estado', 'A')->where('marca.estado', 'A')->where('marca.tipo', 'P')->get();
    }

    public function marcas_secundarias() {
        return $this->belongsToMany('Marca', 'producto_marca', 'producto_id', 'marca_id')->where('producto_marca.estado', 'A')->where('marca.estado', 'A')->where('marca.tipo', 'S')->get();
    }

    public function item() {
        return Item::find($this->item_id);
    }

    public function carritos() {
        return $this->belongsToMany('Carrito', 'carrito_producto', 'carrito_id', 'producto_id');
    }

    public function pedidos() {
        return $this->belongsToMany('Pedido', 'pedido_producto', 'pedido_id', 'producto_id');
    }

    public function nuevo() {
        if (DB::table('item_seccion')
                        ->join('seccion', 'item_seccion.seccion_id', '=', 'seccion.id')
                        ->join('item', 'item_seccion.item_id', '=', 'item.id')
                        ->where('item_seccion.estado', 'A')
                        ->where('item_seccion.item_id', $this->item()->id)
                        ->where('item.estado', 'A')
                        ->where('seccion.estado', 'A')
                        ->where('item_seccion.destacado', 'N')
                        ->get()) {
            return true;
        }
        return false;
    }

    public function oferta() {
        if (DB::table('item_seccion')
                        ->join('seccion', 'item_seccion.seccion_id', '=', 'seccion.id')
                        ->join('item', 'item_seccion.item_id', '=', 'item.id')
                        ->where('item_seccion.estado', 'A')
                        ->where('item_seccion.item_id', $this->item()->id)
                        ->where('item.estado', 'A')
                        ->where('seccion.estado', 'A')
                        ->where('item_seccion.destacado', 'O')
                        ->get()) {
            return true;
        }
        return false;
    }

    public function idiomas() {
        return $this->belongsToMany('Idioma', 'producto_lang', 'producto_id', 'lang_id');
    }

    public function lang() {
        $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

        $producto = Producto::join('producto_lang', 'producto_lang.producto_id', '=', 'producto.id')->where('producto_lang.lang_id', $lang->id)->where('producto.id', $this->id)->first();

        if (is_null($producto)) {
            echo "Por null";
            $lang = Idioma::where('codigo', 'es')->where('estado', 'A')->first();
            $producto = Producto::join('producto_lang', 'producto_lang.producto_id', '=', 'producto.id')->where('producto_lang.lang_id', $lang->id)->where('producto.id', $this->id)->first();
        }

        return $producto;
    }

}
