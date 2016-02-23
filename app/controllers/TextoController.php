<?php

class TextoController extends BaseController {

    protected $folder_name = 'texto';

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
        $respuesta = Texto::agregar(Input::all());

        if ($respuesta['error'] == true) {
            $seccion = Seccion::find(Input::get('seccion_id'));
            $menu = $seccion->menuSeccion()->lang()->url;
            $ancla = '#' . $seccion->estado . $seccion->id;
            return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('error', true);
        } else {
            $menu = $respuesta['data']->item()->seccionItem()->menuSeccion()->lang()->url;
            $ancla = '#' . $respuesta['data']->item()->seccionItem()->estado . $respuesta['data']->item()->seccionItem()->id;

            return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
        }
    }

    public function vistaEditar($id) {
        //Me quedo con el item, buscando por id
        $texto = Texto::buscar($id);
        if ($texto) {
            $this->array_view['item'] = $texto->item();
            $this->array_view['texto'] = $texto;
            return View::make('item.' . $this->folder_name . '.editar-texto', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
        }
    }

    public function editar() {
        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Texto::editar(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/item')->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            $menu = $respuesta['data']->item()->seccionItem()->menuSeccion()->lang()->url;
            $ancla = '#' . $respuesta['data']->item()->seccionItem()->estado . $respuesta['data']->item()->seccionItem()->id;

            return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
        }
    }

}