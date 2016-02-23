<?php

class GaleriaController extends BaseController {

    protected $folder_name = 'galeria';

    public function vistaAgregar($menu_id) {

        $datos = array(
            'titulo' => '',
            'menu_id' => $menu_id
        );

        $seccion = Seccion::agregarSeccion($datos);

        $this->array_view['seccion_id'] = $seccion['data']->id;

        return View::make('item.' . $this->folder_name . '.agregar', $this->array_view);
    }

    public function agregar() {

        //Aca se manda a la funcion agregarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Galeria::agregar(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/item')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            $menu = $respuesta['data']->item()->seccionItem()->menuSeccion()->url;
            $ancla = '#' . $respuesta['data']->item()->seccionItem()->estado . $respuesta['data']->item()->seccionItem()->id;

            return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla);
            //return Redirect::to('admin/item')->with('mensaje', $respuesta['mensaje']);
        }
    }

    public function vistaEditar($id) {
        //Me quedo con el item, buscando por id
        $galeria = Galeria::buscar($id);
        
        if ($galeria) {
            $this->array_view['item'] = $galeria->item();
            $this->array_view['galeria'] = $galeria;
            return View::make('item.' . $this->folder_name . '.editar', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
        }
    }

    public function editar() {
        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Galeria::editar(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/item')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            $menu = $respuesta['data']->seccionItem()->menuSeccion()->url;
            $ancla = '#' . $respuesta['data']->seccionItem()->estado . $respuesta['data']->seccionItem()->id;

            return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla);
        }
    }

}