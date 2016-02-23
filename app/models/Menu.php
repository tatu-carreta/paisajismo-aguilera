<?php

class Menu extends Eloquent {

    protected $table = 'menu';
    protected $fillable = array('orden', 'estado', 'fecha_carga', 'fecha_modificacion', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    public $timestamps = false;

    public static function agregarMenu($input) {

        $respuesta = array();

        $reglas = array(
            'nombre' => array('required', 'max:80', 'unique:menu_lang'),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $url = Str::slug($input['nombre']);
            $orden = 0;
            if (isset($input['tipo_pagina']) && ($input['tipo_pagina'])) {
                switch ($input['tipo_pagina']) {
                    case 1:
                        $url = "";
                        $orden = -1;
                        break;
                    case 2:
                        $url = "contacto";
                        $orden = 99;
                        break;
                    case 3:
                        $url = "carrito";
                        $orden = 98;
                        break;
                }
            }

            $datos = array(
                'orden' => $orden,
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            $menu = static::create($datos);

            $datos_lang = array(
                'nombre' => $input['nombre'],
                'url' => $url,
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
                $menu->idiomas()->attach($idioma->id, $datos_lang);
            }

            if (isset($input['categoria_id']) && ($input['categoria_id'] != "")) {
                $menu->categorias()->attach($input['categoria_id']);
            }

            if (isset($input['menu_id']) && $input['menu_id'] != "") {
                $menu->parent()->attach($input['menu_id'], array('estado' => 'A'));
            }

            if (isset($input['modulo_id']) && $input['modulo_id'] != "") {
                $datosGuardar = array(
                    'estado' => 'A',
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'usuario_id_carga' => Auth::user()->id
                );
                $menu->modulos()->attach($input['modulo_id'], $datosGuardar);
            }

            $idmenu = array('menu_id' => $menu->id,
                'titulo' => "");

            Seccion::agregarSeccion($idmenu);

            $respuesta['mensaje'] = 'Menú creado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $menu;
        }

        return $respuesta;
    }

    public static function editarMenu($input) {
        $respuesta = array();

        $reglas = array(
            'nombre' => array('required', 'max:50'),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

//            echo $lang->id." - ".App::getLocale()." - ".$input['id'];
//            die();
            
            $menu = Menu::join('menu_lang', 'menu_lang.menu_id', '=', 'menu.id')->where('menu_lang.lang_id', $lang->id)->where('menu_lang.estado', 'A')->where('menu.id', $input['id'])->first();
            //$menu = Menu::where($input['id']);
//            $menu_anterior = array(
//                'menu_id' => $menu->id,
//                'nombre' => $menu->nombre,
//                'url' => $menu->url,
//                'orden' => $menu->orden,
//                'fecha_modificacion' => date("Y-m-d H:i:s"),
//                'usuario_id_modificacion' => Auth::user()->id
//            );

            $datos = array(
                'nombre' => $input['nombre'],
                'url' => Str::slug($input['nombre']),
                'fecha_modificacion' => date("Y-m-d H:i:s")
            );
//            $menu->nombre = $input['nombre'];
//            $menu->url = Str::slug($input['nombre']);
//            $menu->fecha_modificacion = date("Y-m-d H:i:s");
//            $menu->save();

            $menu_basic = Menu::find($input['id']);
            
            if (isset($input['editar_asociado']) && ($input['editar_asociado'])) {

                $baja_relacion_menu = DB::table('menu_asociado')->where('menu_id_asociado', $input['id'])->update(['estado' => 'B']);

                if (isset($input['menu_id_asociado']) && ($input['menu_id_asociado'] != "")) {
                    $menu_basic->parent()->attach($input['menu_id_asociado'], array('estado' => 'A'));
                }
            }
            /*
              if ($lang->codigo != Config::get('app.locale')) {
              $datos['url'] = $lang->codigo . "/" . $datos['url'];
              }
             * 
             */

//            var_dump($menu);
//            die();
            
            $menu_modificacion = DB::table('menu_lang')->where('id', $menu->id)->update($datos);

            $respuesta['mensaje'] = 'Menú modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $menu;
        }

        return $respuesta;
    }

    public static function borrarMenu($input) {
        $respuesta = array();

        $reglas = array(
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $menu = Menu::find($input['id']);

            $menu->fecha_baja = date("Y-m-d H:i:s");
            //$menu->nombre = $menu->nombre . "-borrado";
            //$menu->url = $menu->url . "-borrado";
            $menu->estado = 'B';
            $menu->usuario_id_baja = Auth::user()->id;

            $menu->save();

            foreach ($menu->secciones as $seccion) {
                Seccion::borrarSeccion(array('id' => $seccion->id));
            }

            $idiomas = Idioma::where('estado', 'A')->get();

            foreach ($idiomas as $idioma) {
                /*
                  if ($idioma->codigo != Config::get('app.locale')) {
                  $datos_lang['url'] = $idioma->codigo . "/" . $datos_lang['url'];
                  }
                 * 
                 */
                //$menu->idiomas()->attach($idioma->id, $datos_lang);
                
                $menu = Menu::join('menu_lang', 'menu_lang.menu_id', '=', 'menu.id')->where('menu_lang.lang_id', $idioma->id)->where('menu_lang.estado', 'A')->where('menu.id', $input['id'])->first();
            
                $datos = array(
                    'nombre' => $menu->nombre . "-borrado",
                    'url' => $menu->url . "-borrado",
                    'fecha_baja' => date("Y-m-d H:i:s"),
                    'usuario_id_baja' => Auth::user()->id,
                    'estado' => 'B'
                );
                $menu_lang_baja = DB::table('menu_lang')->where('id', $menu->id)->update($datos);
            }

            $respuesta['mensaje'] = 'Menú eliminado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $menu;
        }

        return $respuesta;
    }

    public static function pasarSeccionesACategoria($id) {
        $menu = Menu::find($id);

        $error = false;
        foreach ($menu->secciones as $seccion) {
            if ($seccion->titulo == "") {
                $error = true;
            }
        }

        if (!$error) {
            foreach ($menu->secciones as $seccion) {
                $resultado = Seccion::pasarCategoria($seccion->id);
            }
        } else {
            $resultado['error'] = true;
            $resultado['mensaje'] = "Alguna de las secciones no tiene título, para poder pasar a categoría todas deben tenerlo.";
        }

        return $resultado;
    }

    public static function ordenar($menu_id, $orden) {
        $respuesta = array();

        $reglas = array(
        );
        /*
          $validator = Validator::make($menu_id, $reglas);

          if ($validator->fails()) {
          $respuesta['mensaje'] = $validator;
          $respuesta['error'] = true;
          } else {
         * 
         */

        $menu = Menu::find($menu_id);

        $menu->orden = $orden;

        $menu->save();

        $respuesta['mensaje'] = 'Menú ordenado.';
        $respuesta['error'] = false;
        $respuesta['data'] = $menu;
        //}

        return $respuesta;
    }

    public static function ordenarSubmenu($menu_id, $orden, $menu_id_padre) {
        $respuesta = array();

        $reglas = array(
        );

        $menu = DB::table('menu_asociado')->where('menu_id', $menu_id_padre)->where('menu_id_asociado', $menu_id)->update(['orden' => $orden]);

        $respuesta['mensaje'] = 'Submenú ordenado.';
        $respuesta['error'] = false;
        $respuesta['data'] = $menu;
        //}

        return $respuesta;
    }

    public function categoria() {
        $c = NULL;
        foreach ($this->categorias as $categoria) {
            $c = $categoria;
        }
        return $c;
    }

    public function padre() {
        $menu_padre = NULL;
        foreach ($this->parent as $padres) {
            $menu_padre = $padres;
        }
        return $menu_padre;
    }

    public function categorias() {
        return $this->belongsToMany('Categoria', 'menu_categoria');
    }

    public function secciones() {
        return $this->belongsToMany('Seccion', 'menu_seccion', 'menu_id', 'seccion_id')->where('estado', 'A')->orderBy('orden')->orderBy('seccion_id', 'DESC');
    }

    public function children() {
        return $this->belongsToMany('Menu', 'menu_asociado', 'menu_id', 'menu_id_asociado')->where('menu_asociado.estado', 'A')->where('menu.estado', 'A')->orderBy('menu_asociado.orden');
    }

    public function parent(){
    return $this->belongsToMany('Menu', 'menu_asociado', 'menu_id_asociado');








    }

//Me quedo con los precios del Producto
public function modulos() {
    return $this->belongsToMany('Modulo', 'menu_modulo', 'menu_id', 'modulo_id')->where('menu_modulo.estado', 'A')->select('modulo.nombre', 'modulo.id');
}

public function modulo() {
    $modulo = NULL;


    foreach ($this->modulos as $mod) {
        $modulo = Modulo::find($mod->id);
    }

    return $modulo;
}

public function seccionesConItems() {
    $secciones_con_items = array();
    foreach ($this->secciones as $seccion) {
        if (count($seccion->items) > 0) {
            array_push($secciones_con_items, $seccion);
        }
    }
    return $secciones_con_items;
}

public function idiomas() {
    return $this->belongsToMany('Idioma', 'menu_lang', 'menu_id', 'lang_id');
}

public function lang() {
    $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

    $menu = Menu::join('menu_lang', 'menu_lang.menu_id', '=', 'menu.id')->where('menu_lang.lang_id', $lang->id)->where('menu_lang.estado', 'A')->where('menu.id', $this->id)->first();

    if (is_null($menu)) {
        echo "Por null";
        $lang = Idioma::where('codigo', 'es')->where('estado', 'A')->first();
        $menu = Menu::join('menu_lang', 'menu_lang.menu_id', '=', 'menu.id')->where('menu_lang.lang_id', $lang->id)->where('menu_lang.estado', 'A')->where('menu.id', $this->id)->first();
    }

    return $menu;
}

}
