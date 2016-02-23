<?php

class Categoria extends Eloquent {

    protected $table = 'categoria';
    protected $fillable = array('estado', 'fecha_carga', 'fecha_modificacion', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    public $timestamps = false;

    public static function agregarCategoria($input) {

        $respuesta = array();

        //Se definen las reglas para los datos ingresados
        $reglas = array(
            'nombre' => array('required', 'max:50', 'unique:categoria_lang'),
        );

        //Se realiza la validacion efectiva de los datos con las reglas
        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            //Se definen los datos para crear la categoria
            $datos = array(
//                'nombre' => $input['nombre'],
//                'url' => Str::slug($input['nombre']),
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            //Se crea la Categoria definitivamente
            $categoria = static::create($datos);

            //Se definen los datos necesarios para crear el menu que
            //corresponda con la categoria creada
            $menu_padre = array(
                'nombre' => $input['nombre'],
                'categoria_id' => $categoria->id
            );

            $datos_lang = array(
                'nombre' => $input['nombre'],
                'url' => Str::slug($input['nombre']),
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            $idiomas = Idioma::where('estado', 'A')->get();

            foreach ($idiomas as $idioma) {
                /*
                  if ($idioma->codigo != Config::get('app.locale')) {
                  $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
                  }
                 * 
                 */
                $categoria->idiomas()->attach($idioma->id, $datos_lang);
            }

            //En caso de que la categoria creada, sea hija de alguien
            //se procede a asociar la nueva categoria con la categoria padre
            if (isset($input['categoria_id']) && ($input['categoria_id'] != "")) {
                $categoria->parent()->attach($input['categoria_id'], array('estado' => 'A'));

                //ME QUEDO CON LA CATEGORIA PADRE
                $categoria_padre = Categoria::find($input['categoria_id']);

                //ME QUEDO CON EL MENU AL CUAL PERTENECE LA CATEGORIA PADRE
                //PARA PASARLE EL MENU PADRE AL CUAL PERTENECE EL NUEVO MENU
                foreach ($categoria_padre->menu as $menu) {
                    $menu_id = $menu->id;
                }

                $menu_padre['menu_id'] = $menu_id;
            }

            if (isset($input['modulo_id']) && ($input['modulo_id'] != "")) {
                $menu_padre['modulo_id'] = $input['modulo_id'];
            }

            //Llamo a agregarMenu con los datos antes cargados
            Menu::agregarMenu($menu_padre);

            $respuesta['mensaje'] = 'Categoria creada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $categoria;
        }

        return $respuesta;
    }

    public static function editarCategoria($input) {
        $respuesta = array();

        $reglas = array(
            'nombre' => array('required', 'max:50'),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

//            $categoria = Categoria::find($input['id']);
//
//            $anterior = array(
//                'categoria_id' => $categoria->id,
//                'nombre' => $categoria->nombre,
//                'url' => $categoria->url,
//                'fecha_modificacion' => date("Y-m-d H:i:s"),
//                'usuario_id_modificacion' => Auth::user()->id
//            );
//
//            $categoria->nombre = $input['nombre'];
//            $categoria->url = Str::slug($input['nombre']);
//            $categoria->fecha_modificacion = date("Y-m-d H:i:s");
//
//            $categoria->save();
            
            $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

            $categoria = Categoria::join('categoria_lang', 'categoria_lang.categoria_id', '=', 'categoria.id')->where('categoria_lang.lang_id', $lang->id)->where('categoria_lang.estado', 'A')->where('categoria.id', $input['id'])->first();

            $datos = array(
                'nombre' => $input['nombre'],
                'url' => Str::slug($input['nombre']),
                'fecha_modificacion' => date("Y-m-d H:i:s")
            );
            
            $categoria_basic = Categoria::find($input['id']);
            
            foreach ($categoria_basic->menu as $menu) {
                $menu_id = $menu->id;
            }

//            echo $menu_id;
//            die();
//            
            $dato_menu = array(
                'id' => $menu_id,
                'nombre' => $input['nombre'],
            );

            if (isset($input['categoria_id']) && ($input['categoria_id'] != "")) {

                $baja_relacion_categoria = DB::table('categoria_asociada')->where('categoria_id_asociada', $input['id'])->update(['estado' => 'B']);

                $dato_menu['editar_asociado'] = true;

                if ($input['categoria_id'] != -1) {
                    $categoria_basic->parent()->attach($input['categoria_id'], array('estado' => 'A'));

                    $categoria_padre = Categoria::find($input['categoria_id']);

                    //ME QUEDO CON EL MENU AL CUAL PERTENECE LA CATEGORIA PADRE
                    //PARA PASARLE EL MENU PADRE AL CUAL PERTENECE EL NUEVO MENU

                    foreach ($categoria_padre->menu as $menu) {
                        $dato_menu['menu_id_asociado'] = $menu->id;
                    }
                }
            }

            Menu::editarMenu($dato_menu);

            //$categoria_modificacion = DB::table('categoria_modificacion')->insert($anterior);
            $categoria_modificacion = DB::table('categoria_lang')->where('id', $categoria->id)->update($datos);

            $respuesta['mensaje'] = 'Categoría modificada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $categoria;
        }

        return $respuesta;
    }

    public static function borrarCategoria($input) {
        $respuesta = array();

        $reglas = array(
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $categoria_basic = Categoria::find($input['id']);

            $categoria_basic->fecha_baja = date("Y-m-d H:i:s");
//            $categoria_basic->nombre = $categoria->nombre . "-borrado";
//            $categoria_basic->url = $categoria->url . "-borrado";
            $categoria_basic->estado = 'B';
            $categoria_basic->usuario_id_baja = Auth::user()->id;

            $categoria_basic->save();

            $idiomas = Idioma::where('estado', 'A')->get();

            foreach ($idiomas as $idioma) {
                /*
                  if ($idioma->codigo != Config::get('app.locale')) {
                  $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
                  }
                 * 
                 */
                //$menu->idiomas()->attach($idioma->id, $datos_lang);
                
                $categoria = Categoria::join('categoria_lang', 'categoria_lang.categoria_id', '=', 'categoria.id')->where('categoria_lang.lang_id', $idioma->id)->where('categoria_lang.estado', 'A')->where('categoria.id', $input['id'])->first();
            
                $datos = array(
                    'nombre' => $categoria->nombre . "-borrado",
                    'url' => $categoria->url . "-borrado",
                    'fecha_baja' => date("Y-m-d H:i:s"),
                    'usuario_id_baja' => Auth::user()->id,
                    'estado' => 'B'
                );
                $categoria_lang_baja = DB::table('categoria_lang')->where('id', $categoria->id)->update($datos);
            }
            
            foreach ($categoria_basic->menu as $menu) {
                $menu_id = $menu->id;
            }

            Menu::borrarMenu(['id' => $menu_id]);

            $respuesta['mensaje'] = 'Categoría eliminada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $categoria_basic;
        }

        return $respuesta;
    }

    public function menu() {
        return $this->belongsToMany('Menu', 'menu_categoria');
    }

    public function items() {
        return $this->belongsToMany('Item', 'item_categoria');
    }

    public function children() {
        return $this->belongsToMany('Categoria', 'categoria_asociada', 'categoria_id', 'categoria_id_asociada')->where('categoria_asociada.estado', 'A')->where('categoria.estado', 'A');
    }

    public function parent(){
    return $this->belongsToMany('Categoria', 'categoria_asociada', 'categoria_id_asociada');

    }

public function idiomas() {
    return $this->belongsToMany('Idioma', 'categoria_lang', 'categoria_id', 'lang_id');
}

public function lang() {
    $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

    $categoria = Categoria::join('categoria_lang', 'categoria_lang.categoria_id', '=', 'categoria.id')->where('categoria_lang.lang_id', $lang->id)->where('categoria_lang.estado', 'A')->where('categoria.id', $this->id)->first();

    if (is_null($categoria)) {
        echo "Por null";
        $lang = Idioma::where('codigo', 'es')->where('estado', 'A')->first();
        $categoria = Categoria::join('categoria_lang', 'categoria_lang.categoria_id', '=', 'categoria.id')->where('categoria_lang.lang_id', $lang->id)->where('categoria_lang.estado', 'A')->where('categoria.id', $this->id)->first();
    }

    return $categoria;
}

}
