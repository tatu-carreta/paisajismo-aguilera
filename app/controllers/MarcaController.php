<?php

class MarcaController extends BaseController {

    protected $folder_name = 'marca';

    public function vistaListado() {

        $marcas_principales = Marca::where('tipo', 'P')->where('estado', 'A')->get();
        $marcas_secundarias = Marca::where('tipo', 'S')->where('estado', 'A')->get();

        $this->array_view['marcas_principales'] = $marcas_principales;
        $this->array_view['marcas_secundarias'] = $marcas_secundarias;

        //Hace que se muestre el html lista.blade.php de la carpeta item
        //con los parametros pasados por el array
        return View::make($this->folder_name . '.lista', $this->array_view);
    }

    public function vistaAgregar() {

        return View::make($this->folder_name . '.agregar', $this->array_view);
    }

    public function agregar() {

        //Aca se manda a la funcion agregarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Marca::agregar(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/' . $this->folder_name . '/agregar/')->with('mensaje', $respuesta['mensaje'])->with('error', true);
            //return Redirect::to('admin/marca')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            return Redirect::to('admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('ok', true);
        }
    }

    public function vistaEditar($id) {

        //Me quedo con el item, buscando por id
        $marca = Marca::find($id);

        if ($marca) {
            $this->array_view['marca'] = $marca;
            return View::make($this->folder_name . '.editar', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
        }
    }

    public function editar() {

        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Marca::editar(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/' . $this->folder_name . '/editar/' . Input::get('id'))->with('mensaje', $respuesta['mensaje'])->with('error', true);
            //return Redirect::to('admin/marca')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            return Redirect::to('admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('ok', true);
        }
    }

    public function borrar() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Marca::borrar(Input::all());

        return $respuesta;
    }

    public function quitarImagen() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Marca::quitarImagen(Input::all());

        return $respuesta;
    }

    public function vistaImagen() {

        //Me quedo con el item, buscando por id
        $marca = Marca::find(Input::get('marca_id'));


        if ($marca) {
            $this->array_view['marca'] = $marca;
            return View::make($this->folder_name . '.imagen-marca', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
        }
    }

}
