<?php

class HtmlController extends BaseController {

    protected $folder_name = 'html';

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
        $respuesta = TextoHtml::agregar(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/item')->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            $menu = $respuesta['data']->item()->seccionItem()->menuSeccion()->lang()->url;
            $ancla = '#' . $respuesta['data']->item()->seccionItem()->estado . $respuesta['data']->item()->seccionItem()->id;

            return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
        }
    }

    public function vistaEditar($id) {
        //Me quedo con el item, buscando por id
        $html = TextoHtml::buscar($id);

        if ($html) {
            $this->array_view['item'] = $html->item();
            $this->array_view['html'] = $html;
            return View::make('item.' . $this->folder_name . '.editar-html', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
        }
    }

    public function editar() {
        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = TextoHtml::editar(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/item')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            $menu = $respuesta['data']->item()->seccionItem()->menuSeccion()->lang()->url;
            $ancla = '#' . $respuesta['data']->item()->seccionItem()->estado . $respuesta['data']->item()->seccionItem()->id;

            return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla);
            //return Redirect::to('admin/item')->with('mensaje', $respuesta['mensaje']);
        }
    }

}
