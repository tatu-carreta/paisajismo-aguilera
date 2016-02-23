<?php

class Portfolio extends Item {

    //Tabla de la BD
    protected $table = 'portfolio_simple';
    //Atributos que van a ser modificables
    protected $fillable = array('item_id');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregar($input) {
        //Lo crea definitivamente

        $respuesta = array();

        //Se definen las reglas con las que se van a validar los datos
        //del PRODUCTO
        $reglas = array(
            //'titulo' => array('max:9', 'unique:item'),
            'seccion_id' => array('integer'),
            'imagen_portada_crop' => array('required'),
        );

        //Se realiza la validación
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {

            $messages = $validator->messages();
            /* if ($messages->has('titulo')) {
              $respuesta['mensaje'] = 'El código del producto contiene más de 9 caracteres o ya existe.';
              } else */if ($messages->has('imagen_portada_crop')) {
                $respuesta['mensaje'] = 'Se olvidó de guardar la imagen recortada.';
            } else {
                $respuesta['mensaje'] = 'Los datos necesarios para la obra son erróneos.';
            }

            //Si está todo mal, carga lo que corresponde en el mensaje.

            $respuesta['error'] = true;
        } else {

            if (isset($input['descripcion'])) {

                $input['descripcion'] = $input['descripcion'];
            } else {
                $input['descripcion'] = NULL;
            }


            $item = Item::agregarItem($input);

            if ($item['error']) {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = "Hubo un error al agregar la obra. Compruebe los campos.";
            } else {
                $portfolio_simple = static::create(['item_id' => $item['data']->id]);

                if ($portfolio_simple) {
                    $respuesta['error'] = false;
                    $respuesta['mensaje'] = "Obra creada.";
                    $respuesta['data'] = $portfolio_simple;
                } else {
                    $respuesta['error'] = true;
                    $respuesta['mensaje'] = "Hubo un error al agregar la obra. Compruebe los campos.";
                }
            }
        }
        return $respuesta;
    }

    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
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

            $portfolio_simple = Portfolio::find($input['portfolio_id']);

            $portfolio_simple->save();

            if (isset($input['descripcion'])) {

                $input['descripcion'] = $input['descripcion'];
            } else {
                $input['descripcion'] = NULL;
            }

            $item = Item::editarItem($input);

            $respuesta['mensaje'] = 'Portfolio modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $portfolio_simple;
        }

        return $respuesta;
    }

    public function item() {
        return Item::find($this->item_id);
    }

    public function portfolio_completo() {
        return PortfolioCompleto::where('portfolio_simple_id', $this->id)->first();
    }

    public static function buscar($item_id) {
        return Portfolio::where('item_id', $item_id)->first();
    }

}
