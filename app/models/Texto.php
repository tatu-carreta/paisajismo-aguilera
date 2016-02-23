<?php

class Texto extends Item {

    //Tabla de la BD
    protected $table = 'texto';
    //Atributos que van a ser modificables
    protected $fillable = array('item_id');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregar($input) {
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
            $texto = false;
            $respuesta['mensaje'] = $item['mensaje'];
        } else {
            $texto = static::create(['item_id' => $item['data']->id]);

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
                $texto->idiomas()->attach($idioma->id, $datos_lang);
            }
        }

        if ($texto) {
            $respuesta['error'] = false;
            $respuesta['mensaje'] = "Texto publicado.";
            $respuesta['data'] = $texto;
        } else {
            $respuesta['error'] = true;
            if (!isset($respuesta['mensaje'])) {
                $respuesta['mensaje'] = "Error en el texto. Compruebe los campos.";
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

            $texto = Texto::find($input['texto_id']);

//            $texto->cuerpo = $input['cuerpo'];
//
//            $texto->save();
            $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

                $texto_lang = Texto::join('texto_lang', 'texto_lang.texto_id', '=', 'texto.id')->where('texto_lang.lang_id', $lang->id)->where('texto.id', $texto->id)->first();

                $datos = array(
                    'cuerpo' => $input['cuerpo'],
                );

                $texto_modificacion = DB::table('texto_lang')->where('id', $texto_lang->id)->update($datos);

            if (isset($input['descripcion'])) {

                $input['descripcion'] = $input['descripcion'];
            } else {
                $input['descripcion'] = NULL;
            }

            $item = Item::editarItem($input);

            $respuesta['mensaje'] = 'Texto modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $texto;
        }

        return $respuesta;
    }

    public function item() {
        return Item::find($this->item_id);
    }

    public function noticia() {
        return Noticia::where('texto_id', $this->id)->first();
    }

    public function evento() {
        return Evento::where('texto_id', $this->id)->first();
    }

    public static function buscar($item_id) {
        return Texto::where('item_id', $item_id)->first();
    }
    
    public function idiomas() {
        return $this->belongsToMany('Idioma', 'texto_lang', 'texto_id', 'lang_id');
    }

    public function lang() {
        $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

        $texto = Texto::join('texto_lang', 'texto_lang.texto_id', '=', 'texto.id')->where('texto_lang.lang_id', $lang->id)->where('texto.id', $this->id)->first();

        if (is_null($texto)) {
            echo "Por null";
            $lang = Idioma::where('codigo', 'es')->where('estado', 'A')->first();
            $texto = Texto::join('texto_lang', 'texto_lang.texto_id', '=', 'texto.id')->where('texto_lang.lang_id', $lang->id)->where('texto.id', $this->id)->first();
        }

        return $texto;
    }

}
