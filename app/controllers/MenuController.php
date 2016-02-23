<?php

class MenuController extends BaseController {

    protected $folder_name = 'menu';

    public function vistaListado() {

        $categorias = parent::desplegarCategoria();

        $this->array_view['categorias'] = $categorias;

        $modulos = Modulo::all();

        $this->array_view['modulos'] = $modulos;
        //$this->array_view['prefijo'] = Config::get('app.locale_prefix');
        //return View::make('menu.lista', array('menus' => $menus, 'categorias' => $categorias));
        return View::make($this->folder_name . '.administrar', $this->array_view);
    }

    public function vistaAgregar() {

        return View::make($this->folder_name . '.agregar-boton-estatico', $this->array_view);
    }

    public function agregar() {

        $respuesta = Menu::agregarMenu(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to($this->array_view['prefijo'] . '/admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            return Redirect::to($this->array_view['prefijo'] . '/admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('ok', true);
        }
    }

    public function mostrarInfoMenu($url) {

        $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

        $menu = Menu::join('menu_lang', 'menu_lang.menu_id', '=', 'menu.id')->where('menu_lang.lang_id', $lang->id)->where('menu_lang.estado', 'A')->where('menu_lang.url', $url)->first();
        //$menu = Menu::where('url', $url)->where('estado', 'A')->first();

        if ($menu) {
            $this->array_view['menu'] = $menu;

            $menu_basic = Menu::find($menu->menu_id);

            if (!is_null($menu_basic->categoria())) {
                $this->array_view['ancla'] = Session::get('ancla');

                $hay_datos = false;
                foreach ($menu_basic->secciones as $seccion) {
                    if (count($seccion->items) > 0) {
                        $hay_datos = true;
                    }
                }

                switch ($menu_basic->modulo()->nombre) {
                    case "producto":
                        $marcas = array();
                        foreach ($menu_basic->secciones as $seccion) {
                            if (count($seccion->items) > 0) {
                                foreach ($seccion->items as $item) {
                                    if (!is_null($item->producto())) {
                                        if (!is_null($item->producto()->marca_principal())) {
                                            array_push($marcas, $item->producto()->marca_principal()->id);
                                        }
                                    }
                                }
                            }
                        }

                        if (count($marcas) > 0) {
                            $marcas_principales = Marca::where('tipo', 'P')->whereIn('id', $marcas)->where('estado', 'A')->orderBy('nombre')->get();
                        } else {
                            $marcas_principales = array();
                        }

                        $this->array_view['marcas_principales'] = $marcas_principales;

                        break;
                    default :
                        $menu_basic->modulo()->nombre = 'default';
                        break;
                }
                $textoAgregar = Lang::get('controllers.menu.mostrar_info.' . $menu_basic->modulo()->nombre . '.texto_agregar');
                $texto_modulo = Lang::get('controllers.menu.mostrar_info.' . $menu_basic->modulo()->nombre . '.texto_modulo');

                $this->array_view['html'] = $menu_basic->modulo()->nombre . ".unidad-lista";
                $this->array_view['texto_agregar'] = $textoAgregar;
                $this->array_view['texto_modulo'] = $texto_modulo;

                $this->array_view['hay_datos'] = $hay_datos;

                $this->array_view['menu_basic'] = $menu_basic;

                $this->array_view['type'] = 'M';
                $this->array_view['ang'] = $menu_basic->id;

                return View::make($this->folder_name . ".menu-contenedor", $this->array_view);
            } else {
                $this->array_view['menu_basic'] = $menu_basic;

                $this->array_view['type'] = 'M';
                $this->array_view['ang'] = $menu_basic->id;

                return View::make($this->folder_name . '.menu-estatico', $this->array_view);
            }
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
            //return Redirect::to('/');
        }
    }

    public function mostrarInfoMenuPorMarca($url, $marca) {

        $menu = Menu::where('url', $url)->where('estado', 'A')->first();

        if ($menu) {
            $this->array_view['menu'] = $menu;
            if (!is_null($menu->categoria())) {
                $marcas = array();
                foreach ($menu->secciones as $seccion) {
                    if (count($seccion->items) > 0) {
                        foreach ($seccion->items as $item) {
                            if (!is_null($item->producto())) {
                                if (!is_null($item->producto()->marca_principal())) {
                                    array_push($marcas, $item->producto()->marca_principal()->id);
                                }
                            }
                        }
                    }
                }

                $marcas_principales = Marca::where('tipo', 'P')->whereIn('id', $marcas)->where('estado', 'A')->orderBy('nombre')->get();

                $this->array_view['marcas_principales'] = $marcas_principales;
                $this->array_view['marca_id'] = $marca;
                $this->array_view['ancla'] = Session::get('ancla');
                return View::make($this->folder_name . '.menu-contenedor', $this->array_view);
            } else {
                return View::make($this->folder_name . '.menu-estatico', $this->array_view);
            }
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
            //return Redirect::to('/');
        }
    }

    public function vistaEditar($id) {

        $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

        $menu = Menu::join('menu_lang', 'menu_lang.menu_id', '=', 'menu.id')->where('menu_lang.lang_id', $lang->id)->where('menu_lang.estado', 'A')->where('menu.id', $id)->first();

        if ($menu) {
            $this->array_view['menu'] = $menu;
            return View::make($this->folder_name . '.editar-boton-estatico', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
            //return Redirect::to('/');
        }
    }

    public function editar() {

        $respuesta = Menu::editarMenu(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to($this->array_view['prefijo'] . '/admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            return Redirect::to($this->array_view['prefijo'] . '/admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('ok', true);
        }
    }

    public function borrar() {

        $respuesta = Menu::borrarMenu(Input::all());

        return $respuesta;
    }

    public function pasarSeccionesCategoria() {

        $id = Input::get('menu_id');

        $respuesta = Menu::pasarSeccionesACategoria($id);
        if (Request::ajax()) {
            return Response::json(['mensaje' => $respuesta['mensaje'], 'error' => $respuesta['error'], 'url' => '../']);
        } else {
            if ($respuesta['error'] == true) {
                return Redirect::to('admin/seccion')->with('mensaje', $respuesta['mensaje'])->with('error', true);
            } else {
                return Redirect::to('admin/categoria')->with('mensaje', $respuesta['mensaje'])->with('ok', true);
            }
        }
    }

    public function vistaOrdenar() {
        return View::make($this->folder_name . '.ordenar-menu', $this->array_view);
    }

    public function ordenar() {

        foreach (Input::get('orden') as $key => $menu_id) {
            $respuesta = Menu::ordenar($menu_id, $key);
        }

        return Redirect::to('admin/' . $this->folder_name);
    }

    public function vistaOrdenarSubmenu($menu_id) {
        $menu = Menu::find($menu_id);

        $menus_ordenar = $menu->children;

        $this->array_view['menu_id'] = $menu_id;
        $this->array_view['menus_ordenar'] = $menus_ordenar;

        return View::make($this->folder_name . '.ordenar-submenu', $this->array_view);
    }

    public function ordenarSubmenu() {

        foreach (Input::get('orden') as $key => $menu_id) {
            $respuesta = Menu::ordenarSubmenu($menu_id, $key, Input::get('menu_id'));
        }

        return Redirect::to('admin/' . $this->folder_name);
    }

}
