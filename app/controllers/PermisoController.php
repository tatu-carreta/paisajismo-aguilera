<?php

class PermisoController extends BaseController {

    public function vistaAgregar() {

        $permisos = Permission::all();

        $this->array_view['permisos'] = $permisos;

        return View::make('usuario.permiso.agregar', $this->array_view);
    }

    public function agregar() {

        //Aca se manda a la funcion agregarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Permission::agregar(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/permisos')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            return Redirect::to('admin/permisos')->with('mensaje', $respuesta['mensaje']);
        }
    }

    public function asignarRoles() {

        //Aca se manda a la funcion agregarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Permission::asignarRoles(Input::all());

        if ($respuesta['error'] == true) {
            return Redirect::to('admin/usuarios-permisos')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            return Redirect::to('admin/usuarios-permisos')->with('mensaje', $respuesta['mensaje']);
        }
    }

}
