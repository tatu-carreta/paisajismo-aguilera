<?php

class TextoHtml extends Item {

    //Tabla de la BD
    protected $table = 'html';
    //Atributos que van a ser modificables
    protected $fillable = array('item_id');
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
            $html = static::create(['item_id' => $item['data']->id]);

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
                $html->idiomas()->attach($idioma->id, $datos_lang);
            }
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

//            $html->cuerpo = $input['cuerpo'];
//
//            $html->save();

            $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

            $html_lang = TextoHtml::join('html_lang', 'html_lang.html_id', '=', 'html.id')->where('html_lang.lang_id', $lang->id)->where('html.id', $html->id)->first();

            $datos = array(
                'cuerpo' => $input['cuerpo'],
            );

            $html_modificacion = DB::table('html_lang')->where('id', $html_lang->id)->update($datos);

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
    
    public function idiomas() {
        return $this->belongsToMany('Idioma', 'html_lang', 'html_id', 'lang_id');
    }

    public function lang() {
        $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

        $html = TextoHtml::join('html_lang', 'html_lang.html_id', '=', 'html.id')->where('html_lang.lang_id', $lang->id)->where('html.id', $this->id)->first();

        if (is_null($html)) {
            echo "Por null";
            $lang = Idioma::where('codigo', 'es')->where('estado', 'A')->first();
            $html = TextoHtml::join('html_lang', 'html_lang.html_id', '=', 'html.id')->where('html_lang.lang_id', $lang->id)->where('html.id', $this->id)->first();
        }

        return $html;
    }

}
