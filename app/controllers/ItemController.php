<?php

class ItemController extends BaseController {

    protected $folder_name = 'item';

    public function vistaListado() {

        $items = Item::where('estado', 'A')->get();
        $categorias = Categoria::where('estado', 'A')->get();
        $secciones = Seccion::where('estado', 'A')->get();

        $this->array_view['items'] = $items;
        $this->array_view['categorias'] = $categorias;
        $this->array_view['secciones'] = $secciones;

        //Hace que se muestre el html lista.blade.php de la carpeta item
        //con los parametros pasados por el array
        return View::make($this->folder_name . '.lista', $this->array_view);
    }

    public function vistaAgregar($seccion_id) {
        $this->array_view['seccion_id'] = $seccion_id;
        return View::make($this->folder_name . '.agregar', $this->array_view);
    }

    public function agregar() {

        //Aca se manda a la funcion agregarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso

        $respuesta = Item::agregarItem(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            $menu = $respuesta['data']->seccionItem()->menuSeccion()->url;
            $ancla = '#' . $respuesta['data']->seccionItem()->estado . $respuesta['data']->seccionItem()->id;

            return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
        }
    }

    public function mostrarInfoItem($url) {

        //Me quedo con el item, buscando por url
        $item = Item::where('url', $url)->first();

        $this->array_view['item'] = $item;

        return View::make($this->folder_name . '.ver', $this->array_view);
    }

    public function vistaEditar($id) {

        //Me quedo con el item, buscando por id
        $item = Item::find($id);

        if ($item) {
            $this->array_view['item'] = $item;
            return View::make($this->folder_name . '.editar-item', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
        }
    }

    public function editar() {

        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso

        $respuesta = Item::editarItem(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            $menu = $respuesta['data']->seccionItem()->menuSeccion()->url;
            $ancla = '#' . $respuesta['data']->seccionItem()->estado . $respuesta['data']->seccionItem()->id;

            return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
        }
    }

    public function borrar() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Item::borrarItem(Input::all());

        return $respuesta;
    }

    public function borrarItemSeccion() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Item::borrarItemSeccion(Input::all());

        return $respuesta;
    }

    public function vistaOrdenar($seccion_id) {
        $seccion = Seccion::find($seccion_id);

        $this->array_view['items'] = $seccion->items;
        $this->array_view['seccion'] = $seccion;

        return View::make($this->folder_name . '.lista-por-seccion', $this->array_view);
    }

    public function ordenar() {

        foreach (Input::get('orden') as $key => $item_id) {
            $respuesta = Item::ordenarItemSeccion($item_id, $key, Input::get('seccion_id'));
        }

        $seccion = Seccion::find(Input::get('seccion_id'));

        $menu = $seccion->menuSeccion()->url;
        $ancla = '#' . $seccion->estado . $seccion->id;

        return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
    }

    public function destacarItemSeccion() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Item::destacar(Input::all());

        return $respuesta;
    }

    public function quitarDestacadoItemSeccion() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Item::quitarDestacado(Input::all());

        return $respuesta;
    }

}
