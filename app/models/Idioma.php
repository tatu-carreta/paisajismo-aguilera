<?php

class Idioma extends Eloquent {

    protected $table = 'lang';
    protected $fillable = array('codigo', 'nombre', 'estado', 'fecha_carga', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    public $timestamps = false;

    public static function agregarIdioma($input) {

        $respuesta = array();

        //Se definen las reglas para los datos ingresados
        $reglas = array(
            'codigo' => array('required', 'max:2', 'unique:lang'),
        );

        //Se realiza la validacion efectiva de los datos con las reglas
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            //Se definen los datos para crear la categoria
            $datos = array(
                'codigo' => $input['codigo'],
                'nombre' => $input['nombre'],
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            //Se crea la Categoria definitivamente
            $idioma = static::create($datos);

            $respuesta['mensaje'] = 'Idioma creado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $idioma;
        }

        return $respuesta;
    }

    public static function editarIdioma($input) {
        $respuesta = array();

        $reglas = array(
            'codigo' => array('required', 'max:2', 'unique:lang,codigo,' . $input['id']),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $idioma = Idioma::find($input['id']);

            $idioma->nombre = $input['nombre'];
            $idioma->codigo = $input['codigo'];
            
            $idioma->save();

            $respuesta['mensaje'] = 'Idioma modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $idioma;
        }

        return $respuesta;
    }

    public static function borrarIdioma($input) {
        $respuesta = array();

        $reglas = array(
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $idioma = Idioma::find($input['id']);

            $idioma->fecha_baja = date("Y-m-d H:i:s");
            $idioma->estado = 'B';
            $idioma->usuario_id_baja = Auth::user()->id;

            $idioma->save();

            $respuesta['mensaje'] = 'Idioma eliminado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $idioma;
        }

        return $respuesta;
    }

    public function menus() {
        return $this->belongsToMany('Menu', 'menu_lang', 'menu_id', 'lang_id');
    }
    
    public function categorias() {
        return $this->belongsToMany('Categoria', 'categoria_lang', 'categoria_id', 'lang_id');
    }
    
}
