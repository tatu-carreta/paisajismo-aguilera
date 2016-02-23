<?php

class Seccion_v2 extends Eloquent {

    protected $table = 'seccion';
    protected $fillable = array('titulo', 'estado', 'fecha_carga', 'fecha_modificacion', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    public $timestamps = false;

    public static function agregarSeccion($input) {

        $respuesta = array();

        $reglas = array(
            'titulo' => array('max:50'),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $datos = array(
                'titulo' => $input['titulo'],
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            $seccion = static::create($datos);
            $seccion->menu()->attach($input['menu_id']);

            $respuesta['mensaje'] = 'Sección creada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $seccion;
        }

        return $respuesta;
    }

    public static function editarSeccion($input) {
        $respuesta = array();

        $reglas = array(
            'titulo' => array('max:50'),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $seccion = Seccion::find($input['id']);

            $seccion->titulo = $input['titulo'];
            $seccion->fecha_modificacion = date("Y-m-d H:i:s");

            $seccion->save();

            $respuesta['mensaje'] = 'Sección modificada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $seccion;
        }

        return $respuesta;
    }

    public static function borrarSeccion($input) {
        $respuesta = array();

        $reglas = array(
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $seccion = Seccion::find($input['id']);

            $seccion->fecha_baja = date("Y-m-d H:i:s");
            $seccion->estado = 'B';
            $seccion->usuario_id_baja = Auth::user()->id;

            $seccion->save();

            $respuesta['mensaje'] = 'Sección eliminada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $seccion;
        }

        return $respuesta;
    }

    public static function pasarCategoria($id) {
        $seccion = Seccion::find($id);

        foreach ($seccion->menu as $menu) {
            $menu_id = $menu->id;
        }

        $menu = Menu::find($menu_id);

        foreach ($menu->categorias as $categoria) {
            $categoria_id = $categoria->id;
        }

        if ($seccion->titulo != "") {
            $nombre = $seccion->titulo;
        } else {
            $nombre = $menu->nombre;
        }

        $datosCategoria = array(
            'nombre' => $nombre,
            'categoria_id' => $categoria_id
        );

        $categoria_creada = Categoria::agregarCategoria($datosCategoria);

        $categoria = Categoria::find($categoria_creada['data']->id);

        foreach ($categoria->menu as $menu) {
            $menu_id = $menu->id;
        }

        $menu_nuevo = Menu::find($menu_id);

        foreach ($menu_nuevo->secciones as $seccion_menu) {
            $seccion_id = $seccion_menu->id;
        }

        foreach ($seccion->items as $item) {
            $item->secciones()->attach($seccion_id, array('estado' => 'A'));
        }

        $resultado = Seccion::borrarSeccion(['id' => $seccion->id]);

        return $resultado;
    }

    public static function ordenarSeccionMenu($seccion_id, $orden, $menu_id) {
        $respuesta = array();
        /*
          $reglas = array(
          );

          $validator = Validator::make($input, $reglas);

          if ($validator->fails()) {
          $respuesta['mensaje'] = $validator;
          $respuesta['error'] = true;
          } else {
         * 
         */

        $input = array(
            'menu_id' => $menu_id,
            'seccion_id' => $seccion_id,
        );

        $seccion = DB::table('menu_seccion')->where($input)->update(array('orden' => $orden));

        $seccion_p = Seccion::find($seccion_id);

        $respuesta['mensaje'] = 'Sección ordenada.';
        $respuesta['error'] = false;
        $respuesta['data'] = $seccion_p;
        //}

        return $respuesta;
    }

    public function menuSeccion() {
        $menu = NULL;
        foreach ($this->menu as $menus) {
            $menu = $menus;
        }
        return $menu;
    }

    public function items_por_marca($marca_id) {
        $items = array();
        foreach ($this->items as $item) {
            if (!is_null($item->producto()->marca_principal())) {
                if ($item->producto()->marca_principal()->id == $marca_id) {
                    array_push($items, $item);
                }
            }
        }
        return $items;
    }

    public function slideIndex() {
        return Slide::where('estado', 'A')->where('tipo', 'I')->where('seccion_id', $this->id)->first();
    }

    public function menu() {
        return $this->belongsToMany('Menu', 'menu_seccion')->where('estado', 'A');
    }

    public function items() {
        return $this->belongsToMany('Item', 'item_seccion')->where('item_seccion.estado', 'A')->where('item.estado', 'A')->orderBy('item_seccion.destacado', 'DESC')->orderBy('item_seccion.orden')->orderBy('item.id', 'DESC');
    }

    public function destacados() {
        return $this->belongsToMany('Item', 'item_seccion')->where('item_seccion.estado', 'A')->where('item.estado', 'A')->where('destacado', 'A')->orderBy('item_seccion.orden');
    }

    public function ids_items_destacados() {
        return $this->belongsToMany('Item', 'item_seccion')->where('item_seccion.estado', 'A')->where('item.estado', 'A')->where('destacado', 'A')->orderBy('item_seccion.orden')->lists('item_id');
    }

    public function slides() {
        return $this->hasMany('Slide', 'seccion_id')->where('estado', 'A')->where('tipo', 'E');
    }

    public function items_noticias() {
        $noticias = Noticia::orderBy('fecha', 'DESC')->simplePaginate(10);

        $items = array();

        foreach ($noticias as $noticia) {
            $item = Item::find($noticia->texto()->item()->id);
            if (in_array($this->id, $item->secciones->lists('id'))) {
                array_push($items, $item);
            }
        }

        $result = array(
            'paginador' => $noticias,
            'noticias' => $items
        );

        return $result;
    }

}
