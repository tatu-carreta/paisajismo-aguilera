<?php

class TextoHtml_v2 extends Item {

    //Tabla de la BD
    protected $table = 'html';
    //Atributos que van a ser modificables
    protected $fillable = array('item_id', 'cuerpo');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregar($input) {
        //Lo crea definitivamente
        //Lo crea definitivamente

        if (isset($input['descripcion'])) {

            $input['descripcion'] = $input['descripcion'];
        } else {
            $input['descripcion'] = NULL;
        }

        $input['es_texto'] = true;

        $item = Item::agregarItem($input);

        if (isset($input['cuerpo'])) {

            $cuerpo = $input['cuerpo'];
        } else {
            $cuerpo = NULL;
        }

        if (!isset($item['data'])) {
            $html = false;
            $respuesta['mensaje'] = $item['mensaje'];
        } else {
            $html = static::create(['item_id' => $item['data']->id, 'cuerpo' => $cuerpo]);
        }

        if ($html) {
            $respuesta['error'] = false;
            $respuesta['mensaje'] = "Html publicado.";
            $respuesta['data'] = $html;
        } else {
            $respuesta['error'] = true;
            if (!isset($respuesta['mensaje'])) {
                $respuesta['mensaje'] = "Error en el html. Compruebe los campos.";
            }
        }

        return $respuesta;
    }

    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $html = TextoHtml::find($input['html_id']);

            $html->cuerpo = $input['cuerpo'];

            $html->save();

            $input['descripcion'] = NULL;

            $item = Item::editarItem($input);

            $respuesta['mensaje'] = 'HTML modificado!';
            $respuesta['error'] = false;
            $respuesta['data'] = $html;
        }

        return $respuesta;
    }

    public function item() {
        return Item::find($this->item_id);
    }

    public static function buscar($item_id) {
        return TextoHtml::where('item_id', $item_id)->first();
    }

}
