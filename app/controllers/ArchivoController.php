<?php

class ArchivoController extends BaseController {

    public function borrar() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Archivo::borrar(Input::all());

        return $respuesta;
    }

}