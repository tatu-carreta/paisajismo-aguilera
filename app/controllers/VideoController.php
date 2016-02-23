<?php

class VideoController extends BaseController {

    protected $folder_name = 'video';

    public function verListado() {

        $videos = Video::where('estado', 'A')->get();

        $this->array_view['videos'] = $videos;

        return View::make($this->folder_name . '.listado', $this->array_view);
    }

    public function vistaAgregar() {

        $this->array_view['item_id'] = 5;

        return View::make($this->folder_name . '.agregar', $this->array_view);
    }

    public function agregarYoutube() {

        $hosts = array('youtube.com', 'www.youtube.com');
        $paths = array('/watch');

        $infoValidate = Video::validarUrl(Input::get('video'), $hosts, $paths);

        if ($infoValidate['estado']) {
            if ($ID_video = Youtube::parseVIdFromURL(Input::get('video'))) {

                $datos = array(
                    'ID_video' => $ID_video
                );

                //Aca se manda a la funcion agregarItem de la clase Item
                //y se queda con la respuesta para redirigir cual sea el caso
                $respuesta = Video::agregarYoutube($datos);

                return Redirect::to('admin/' . $this->folder_name)->withErrors($respuesta['mensaje'])->withInput();
            } else {
                $infoValidate['texto'] = 'Hubo problemas con la información del video. Intente más tarde.';
            }
        }
        //echo $infoValidate['texto'];
        return Redirect::to('admin/' . $this->folder_name . '/agregar')->withErrors($infoValidate['texto'])->withInput();
    }
    
    public function borrar() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Video::borrar(Input::all());

        return $respuesta;
    }

    /*
      public function vistaEditar($id) {
      //Me quedo con el item, buscando por id
      $texto = Texto::buscar($id);
      if ($texto) {
      $this->array_view['item'] = $texto->item();
      $this->array_view['texto'] = $texto;
      return View::make('item.' . $this->folder_name . '.editar-texto', $this->array_view);
      } else {
      $this->array_view['texto'] = 'Página de Error!!';
      return View::make($this->project_name . '-error', $this->array_view);
      }
      }

      public function editar() {
      //Aca se manda a la funcion editarItem de la clase Item
      //y se queda con la respuesta para redirigir cual sea el caso
      $respuesta = Texto::editar(Input::all());

      if ($respuesta['error'] == true) {
      return Redirect::to('admin/item')->withErrors($respuesta['mensaje'])->withInput();
      } else {
      $menu = $respuesta['data']->item()->seccionItem()->menuSeccion()->url;
      $ancla = '#' . $respuesta['data']->item()->seccionItem()->estado . $respuesta['data']->item()->seccionItem()->id;

      return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla);
      }
      }
     * 
     */
}
