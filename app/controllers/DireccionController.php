<?php

class DireccionController extends BaseController {

    protected $folder_name = 'direccion';

    /*
      public function vistaListado() {
      $secciones = Seccion::where('estado', 'A')->get();

      $this->array_view['secciones'] = $secciones;

      return View::make('seccion.lista', $this->array_view);
      }
     * 
     */

    public function vistaAgregar() {

        return View::make($this->folder_name . '.' . $this->project_name . '-agregar', $this->array_view);
    }

    public function agregar() {

        $respuesta = Direccion::agregar(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('/')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            return Redirect::to('/')->with('mensaje', $respuesta['mensaje']);
        }
    }

    public function mostrarInfo($id) {

        $direccion = Direccion::find($id);

        return View::make($this->folder_name . '.' . $this->project_name . '-ver', array('direccion' => $direccion));
    }

/*
    public function vistaEditar($id) {

        $seccion = Seccion::find($id);

        if ($seccion) {
            $this->array_view['seccion'] = $seccion;
            return View::make('seccion.editar-seccion', $this->array_view);
        } else {
            $this->array_view['texto'] = 'Error al cargar la página.';
            return View::make($this->project_name . '-error', $this->array_view);
        }
    }

    public function editar() {

        $respuesta = Seccion::editarSeccion(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/seccion')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            $menu = $respuesta['data']->menuSeccion()->url;
            $ancla = '#' . $respuesta['data']->estado . $respuesta['data']->id;

            return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla);
        }
    }

    public function borrar() {

        $respuesta = Seccion::borrarSeccion(Input::all());

        return $respuesta;
    }

    public function vistaOrdenar($menu_id) {
        $menu = Menu::find($menu_id);

        $this->array_view['secciones'] = $menu->secciones;
        $this->array_view['menu'] = $menu;

        return View::make('seccion.lista-por-menu', $this->array_view);
    }

    public function ordenar() {

        foreach (Input::get('orden') as $key => $seccion_id) {
            $respuesta = Seccion::ordenarSeccionMenu($seccion_id, $key, Input::get('menu_id'));
        }

        $menu = $respuesta['data']->menuSeccion()->url;
        $ancla = '#' . $respuesta['data']->estado . $respuesta['data']->id;

        return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla);

        //return Redirect::action('SeccionController@vistaOrdenar', array(Input::get('menu_id')));
    }
 * 
 */
}
