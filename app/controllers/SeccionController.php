<?php

class SeccionController extends BaseController {

    protected $folder_name = 'seccion';

    public function vistaListado() {
        $secciones = Seccion::where('estado', 'A')->get();

        $this->array_view['secciones'] = $secciones;

        return View::make($this->folder_name . '.lista', $this->array_view);
    }

    public function vistaAgregar($menu_id) {

        $this->array_view['menu_id'] = $menu_id;

        return View::make($this->folder_name . '.agregar', $this->array_view);
    }

    public function agregar() {

        $respuesta = Seccion::agregarSeccion(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to($this->array_view['prefijo'].'/admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            $menu = $respuesta['data']->menuSeccion()->lang()->url;
            $ancla = '#' . $respuesta['data']->estado . $respuesta['data']->id;

            return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
        }
    }

    public function mostrarInfoSeccion($id) {

        $seccion = Seccion::find($id);

        return View::make($this->folder_name . '.ver', array('seccion' => $seccion));
    }

    public function vistaEditar($id) {

        $seccion = Seccion::find($id);

        if ($seccion) {
            $this->array_view['seccion'] = $seccion;
            return View::make($this->folder_name . '.editar', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
        }
    }

    public function editar() {

        $respuesta = Seccion::editarSeccion(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to($this->array_view['prefijo'].'/admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            $menu = $respuesta['data']->menuSeccion()->lang()->url;
            $ancla = '#' . $respuesta['data']->estado . $respuesta['data']->id;

            return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
        }
    }

    public function borrar() {

        $respuesta = Seccion::borrarSeccion(Input::all());

        return $respuesta;
    }

    public function vistaOrdenar($menu_id) {
        $menu = Menu::find($menu_id);

        $this->array_view['secciones'] = $menu->seccionesConItems();
        $this->array_view['menu'] = $menu;

        return View::make($this->folder_name . '.ordenar', $this->array_view);
    }

    public function ordenar() {

        foreach (Input::get('orden') as $key => $seccion_id) {
            $respuesta = Seccion::ordenarSeccionMenu($seccion_id, $key, Input::get('menu_id'));
        }

        $menu = $respuesta['data']->menuSeccion()->url;
        $ancla = '#' . $respuesta['data']->estado . $respuesta['data']->id;

        return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);

        //return Redirect::action('SeccionController@vistaOrdenar', array(Input::get('menu_id')));
    }

}
