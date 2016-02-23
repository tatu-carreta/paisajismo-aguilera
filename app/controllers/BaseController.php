<?php

class BaseController extends Controller {

    protected $project_name = 'tc';
    protected $array_view = array();

    public function __construct() {
        $this->array_view['type'] = 'X';
        $this->array_view['ang'] = 'X';
        $this->array_view['project_name'] = $this->project_name;
        $this->array_view['menus'] = $this->desplegarMenu();
        $this->array_view['menu_estatico'] = $this->menuEstatico();
        $this->array_view['menu_dinamico'] = $this->menuDinamico();
        $this->array_view['slide_index'] = $this->slideIndex();
        $this->array_view['prefijo'] = Config::get('app.locale_prefix');
        $this->configureLocale();
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout() {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    /**
     * Action used to set the application locale.
     * 
     */
    public function setLocale() {
        $mLocale = Request::segment(2, Config::get('app.locale')); // Get parameter from URL.
        if (in_array($mLocale, Config::get('app.languages'))) {
            App::setLocale($mLocale);
            Session::put('locale', $mLocale);
            Cookie::forever('locale', $mLocale);
        }

        if ((Request::segment(3) == 'M') && (is_numeric(Request::segment(4)))) {
            $menu_id = Request::segment(4);

            $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();

            $menu = Menu::join('menu_lang', 'menu_lang.menu_id', '=', 'menu.id')->where('menu_lang.lang_id', $lang->id)->where('menu_lang.estado', 'A')->where('menu_lang.menu_id', $menu_id)->first();

            return Redirect::to($this->array_view['prefijo'] . '/' . $menu->url);
        } else {
            return Redirect::back();
        }
    }

    /**
     * Detect and set application localization environment (language).
     * NOTE: Don't foreget to ADD/SET/UPDATE the locales array in app/config/app.php!
     *
     */
    private function configureLocale() {
        // Set default locale.
        $mLocale = Config::get('app.locale');

        // Has a session locale already been set?
        if (!Session::has('locale')) {
            // No, a session locale hasn't been set.
            // Was there a cookie set from a previous visit?
            $mFromCookie = Cookie::get('locale', null);
            if ($mFromCookie != null && in_array($mFromCookie, Config::get('app.languages'))) {
                // Cookie was previously set and it's a supported locale.
                $mLocale = $mFromCookie;
            } else {
                // No cookie was set.
                // Attempt to get local from current URI.
                $mFromURI = Request::segment(1);
                if ($mFromURI != null && in_array($mFromURI, Config::get('app.languages'))) {
                    // supported locale
                    $mLocale = $mFromURI;
                } else {
                    // attempt to detect locale from browser.
                    $mFromBrowser = substr(Request::server('http_accept_language'), 0, 2);
                    if ($mFromBrowser != null && in_array($mFromBrowser, Config::get('app.languages'))) {
                        // browser lang is supported, use it.
                        $mLocale = $mFromBrowser;
                    } // $mFromBrowser
                } // $mFromURI
            } // $mFromCookie

            Session::put('locale', $mLocale);
            Cookie::forever('locale', $mLocale);
        } // Session?
        else {
            // session locale is available, use it.
            $mLocale = Session::get('locale');
        } // Session?
        // set application locale for current session.
        App::setLocale($mLocale);
    }

    protected function desplegarMenu() {
        /*
         * FILTRO PARA MOSTRAR SOLAMENTE LOS MENU PADRE
         */
        $menus_asociados = DB::table('menu_asociado')->where('estado', 'A')->lists('menu_id_asociado');
        if ($menus_asociados) {
            $menus = Menu::where('estado', 'A')->whereNotIn('id', $menus_asociados)->orderBy('orden')->get();
        } else {
            $menus = Menu::where('estado', 'A')->orderBy('orden')->get();
        }

        return $menus;
    }

    protected function menuEstatico() {
        $menu_desplegado = $this->desplegarMenu();

        $menus = array();

        foreach ($menu_desplegado as $menu) {
            if (is_null($menu->categoria())) {
                array_push($menus, $menu);
            }
        }

        return $menus;
    }

    protected function menuDinamico() {
        $menu_desplegado = $this->desplegarMenu();

        $menus = array();

        foreach ($menu_desplegado as $menu) {
            if (!is_null($menu->categoria())) {
                array_push($menus, $menu);
            }
        }

        return $menus;
    }

    protected function desplegarCategoria() {
        /*
         * FILTRO PARA MOSTRAR SOLAMENTE LOS MENU PADRE
         */
        $categorias_asociadas = DB::table('categoria_asociada')->where('estado', 'A')->lists('categoria_id_asociada');

        if ($categorias_asociadas) {
            $categorias = Categoria::where('estado', 'A')->whereNotIn('id', $categorias_asociadas)->get();
        } else {
            $categorias = Categoria::where('estado', 'A')->get();
        }

        return $categorias;
    }

    protected function itemsDestacados() {
        /*
         * FILTRO PARA MOSTRAR SOLAMENTE LOS MENU PADRE
         */
        $items_destacados = DB::table('item')
                ->join('item_seccion', 'item.id', '=', 'item_seccion.item_id')
                ->join('seccion', 'item_seccion.seccion_id', '=', 'seccion.id')
                ->join('item_lang', 'item.id', '=', 'item_lang.item_id')
                ->join('lang', 'lang.id', '=', 'item_lang.lang_id')->where('lang.codigo', App::getLocale())->where('item_lang.estado', 'A')
                ->where('item.estado', 'A')
                ->where('item_seccion.estado', 'A')
                ->where('item_seccion.destacado', 'A')
                ->where('seccion.estado', 'A')
                ->orderBy('item.id', 'desc')
                ->limit(5)
                ->select('item.id as item_id', 'item_lang.titulo as item_titulo', 'item_lang.descripcion as item_descripcion', 'item_lang.url as item_url', 'seccion.id as seccion_id')
                ->get();

        $items = $items_destacados;

        if ($items_destacados) {
            $items = array();
            foreach ($items_destacados as $item) {
                $item_db = Item::find($item->item_id);

                array_push($items, $item_db);
            }
        }

        return $items;
    }

    protected function itemsNuevos($limit) {
        /*
         * FILTRO PARA MOSTRAR SOLAMENTE LOS MENU PADRE
         */
        $items_destacados = DB::table('item')
                ->join('item_seccion', 'item.id', '=', 'item_seccion.item_id')
                ->join('seccion', 'item_seccion.seccion_id', '=', 'seccion.id')
                ->join('producto', 'item.id', '=', 'producto.item_id')
                ->join('item_lang', 'item.id', '=', 'item_lang.item_id')
                ->join('lang', 'lang.id', '=', 'item_lang.lang_id')->where('lang.codigo', App::getLocale())->where('item_lang.estado', 'A')
                ->where('item.estado', 'A')
                ->where('item_seccion.estado', 'A')
                ->where('item_seccion.destacado', 'N')
                ->where('seccion.estado', 'A')
                ->orderBy('item.id', 'desc')
                ->limit($limit)
                ->select('item.id as item_id', 'item_lang.titulo as item_titulo', 'item_lang.descripcion as item_descripcion', 'item_lang.url as item_url', 'seccion.id as seccion_id')
                ->get();

        $items = $items_destacados;

        if ($items_destacados) {
            $items = array();
            $items_id = array();
            foreach ($items_destacados as $item) {
                if (!in_array($item->item_id, $items_id)) {
                    $item_db = Item::find($item->item_id);
                    array_push($items, $item_db);
                    array_push($items_id, $item->item_id);
                }
            }
        }

        return $items;
    }

    protected function itemsOferta($limit) {
        /*
         * FILTRO PARA MOSTRAR SOLAMENTE LOS MENU PADRE
         */
        $items_destacados = DB::table('item')
                ->join('item_seccion', 'item.id', '=', 'item_seccion.item_id')
                ->join('seccion', 'item_seccion.seccion_id', '=', 'seccion.id')
                ->join('producto', 'item.id', '=', 'producto.item_id')
                ->join('item_lang', 'item.id', '=', 'item_lang.item_id')
                ->join('lang', 'lang.id', '=', 'item_lang.lang_id')->where('lang.codigo', App::getLocale())->where('item_lang.estado', 'A')
                ->where('item.estado', 'A')
                ->where('item_seccion.estado', 'A')
                ->where('item_seccion.destacado', 'O')
                ->where('seccion.estado', 'A')
                ->orderBy('item.id', 'desc')
                ->limit($limit)
                ->select('item.id as item_id', 'item_lang.titulo as item_titulo', 'item_lang.descripcion as item_descripcion', 'item_lang.url as item_url', 'seccion.id as seccion_id')
                ->distinct()
                ->get();

        $items = $items_destacados;

        if ($items_destacados) {
            $items = array();
            $items_id = array();
            foreach ($items_destacados as $item) {

                if (!in_array($item->item_id, $items_id)) {
                    $item_db = Item::find($item->item_id);
                    array_push($items, $item_db);
                    array_push($items_id, $item->item_id);
                }
            }
        }

        return $items;
    }

    protected function ultimosProductos($destacados, $limit) {
        /*
         * FILTRO PARA MOSTRAR SOLAMENTE LOS MENU PADRE
         */
        if (count($destacados) > 0) {

            $items_destacados = DB::table('item')
                    ->join('item_seccion', 'item.id', '=', 'item_seccion.item_id')
                    ->join('seccion', 'item_seccion.seccion_id', '=', 'seccion.id')
                    ->join('producto', 'item.id', '=', 'producto.item_id')
                    ->join('item_lang', 'item.id', '=', 'item_lang.item_id')
                ->join('lang', 'lang.id', '=', 'item_lang.lang_id')->where('lang.codigo', App::getLocale())->where('item_lang.estado', 'A')
                    ->where('item.estado', 'A')
                    ->where('item_seccion.estado', 'A')
                    ->whereNull('item_seccion.destacado')
                    ->where('seccion.estado', 'A')
                    ->whereNotIn('item.id', $destacados)
                    ->orderBy('item.fecha_modificacion', 'desc')
                    //->limit($limit)
                    ->select('item.id as item_id', 'item_lang.titulo as item_titulo', 'item_lang.descripcion as item_descripcion', 'item_lang.url as item_url', 'seccion.id as seccion_id')
                    ->distinct()
                    ->get();
        } else {
            $items_destacados = DB::table('item')
                    ->join('item_seccion', 'item.id', '=', 'item_seccion.item_id')
                    ->join('seccion', 'item_seccion.seccion_id', '=', 'seccion.id')
                    ->join('producto', 'item.id', '=', 'producto.item_id')
                    ->join('item_lang', 'item.id', '=', 'item_lang.item_id')
                ->join('lang', 'lang.id', '=', 'item_lang.lang_id')->where('lang.codigo', App::getLocale())->where('item_lang.estado', 'A')
                    ->where('item.estado', 'A')
                    ->where('item_seccion.estado', 'A')
                    ->whereNull('item_seccion.destacado')
                    ->where('seccion.estado', 'A')
                    ->orderBy('item.fecha_modificacion', 'desc')
                    //->limit($limit)
                    ->select('item.id as item_id', 'item_lang.titulo as item_titulo', 'item_lang.descripcion as item_descripcion', 'item_lang.url as item_url', 'seccion.id as seccion_id')
                    ->distinct()
                    ->get();
        }

        $items = $items_destacados;

        if ($items_destacados) {
            $items = array();
            $items_id = array();
            $i = 0;
            foreach ($items_destacados as $item) {

                if (!in_array($item->item_id, $items_id) && ($i != $limit)) {
                    $item_db = Item::find($item->item_id);
                    array_push($items, $item_db);
                    array_push($items_id, $item->item_id);

                    $i++;
                }
            }
        }

        return $items;
    }

    protected function slideIndex() {
        return Slide::where('estado', 'A')->where('tipo', 'I')->orderBy('id', 'desc')->first();
    }

    protected function seccionesDinamicas() {
        $secciones_dinamicas = array();

        $secciones = Seccion::where('estado', 'A')->get();

        foreach ($secciones as $seccion) {
            if (!is_null($seccion->menuSeccion()->categoria())) {
                array_push($secciones_dinamicas, $seccion);
            }
        }

        return $secciones_dinamicas;
    }

    protected function seccionesEstaticas() {
        $secciones_estaticas = array();

        $secciones = Seccion::where('estado', 'A')->get();

        foreach ($secciones as $seccion) {
            if (is_null($seccion->menuSeccion()->categoria())) {
                array_push($secciones_estaticas, $seccion);
            }
        }

        return $secciones_estaticas;
    }

}
