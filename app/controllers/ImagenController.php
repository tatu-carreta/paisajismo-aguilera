<?php

class ImagenController extends BaseController {

    public function borrar() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Imagen::borrar(Input::all());

        return $respuesta;
    }

    public function uploadImagenCrop() {

        if (!empty(Input::hasFile('file')) && (!empty(Input::hasFile('imagen_ampliada')))) {

            $img_crop = Input::file('file');

            $img_ampliada = Input::file('imagen_ampliada');

            $respuesta = Imagen::uploadImageAngular($img_crop, $img_ampliada);

            echo json_encode($respuesta);
        } else {
            echo 'No image';
        }
    }

    public function uploadGaleriaSlideHome() {

        if (!empty(Input::hasFile('file'))) {

            $img_slide = Input::file('file');

            $respuesta = Imagen::uploadImageAngularSlide($img_slide);

            echo json_encode($respuesta);
        } else {
            echo 'No image';
        }
        die();
    }

    public function ordenar() {

        foreach (Input::get('orden') as $key => $imagen_id) {
            if ($key == 0) {
                $destacado = 'A';
            } else {
                $destacado = NULL;
            }
            $respuesta = Imagen::ordenarImagenItem($imagen_id, $key, Input::get('item_id'), $destacado);
        }

        $item = Item::find(Input::get('item_id'));

        $menu = $item->seccionItem()->menuSeccion()->modulo()->nombre;

        return Redirect::to('/' . $menu . '/' . $item->url)->with('mensaje', $respuesta['mensaje'])->with('ok', true);
    }

}
