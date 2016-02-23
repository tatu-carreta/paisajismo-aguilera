<?php

class PortfolioCompletoController extends BaseController {

    protected $folder_name = 'portfolio_completo';

    public function vistaListado() {

        $items_borrados = Item::where('estado', 'B')->lists('id');

        if (count($items_borrados) > 0) {

            $portfolios = PortfolioCompleto::whereNotIn('item_id', $items_borrados)->get();
        } else {
            $portfolios = PortfolioCompleto::all();
        }
        $categorias = Categoria::where('estado', 'A')->get();
        $secciones = Seccion::where('estado', 'A')->get();

        $this->array_view['portfolios'] = $portfolios;
        $this->array_view['categorias'] = $categorias;
        $this->array_view['secciones'] = $secciones;

        //Hace que se muestre el html lista.blade.php de la carpeta item
        //con los parametros pasados por el array
        return View::make($this->folder_name . '.lista', $this->array_view);
    }

    public function mostrarInfo($url) {
        $lang = Idioma::where('codigo', App::getLocale())->where('estado', 'A')->first();
        
        $item_lang = Item::join('item_lang', 'item_lang.item_id', '=', 'item.id')->where('item_lang.lang_id', $lang->id)->where('item_lang.url', $url)->first();
        
        //Me quedo con el item, buscando por url
        //$item = Item::where('url', $url)->first();
        $item = Item::find($item_lang->item_id);

        $this->array_view['item'] = $item;

        return View::make($this->folder_name . '.detalle', $this->array_view);
    }

    public function vistaAgregar($seccion_id) {

        $this->array_view['seccion_id'] = $seccion_id;
        
        $seccion = Seccion::find($seccion_id);
        
        $modulo = $seccion->menuSeccion()->modulo();

        $this->array_view['menues'] = $modulo->menus;
        
        $this->array_view['titulo_texto'] = 'Nueva obra';
        $this->array_view['titulo_modulo_imagen'] = 'Imágenes de la obra';
        
        $this->array_view['modulo_pagina_nombre'] = $modulo->nombre;

        return View::make($this->folder_name . '.agregar', $this->array_view);
    }

    public function agregar() {

        //Aca se manda a la funcion agregarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = PortfolioCompleto::agregar(Input::all());

        /*
          if ($respuesta['error'] == true) {
          return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
          } else {
          return Redirect::to('admin/producto')->with('mensaje', $respuesta['mensaje']);
          }
         * 
         */
        if ($respuesta['error'] == true) {
            $seccion = Seccion::find(Input::get('seccion_id'));

            $menu = $seccion->menuSeccion()->lang()->url;
            $ancla = '#' . $seccion->estado . $seccion->id;

            return Redirect::to($this->array_view['prefijo'].'/admin/' . $this->folder_name . '/agregar/' . $seccion->id)->with('mensaje', $respuesta['mensaje'])->with('error', true); //->with('ancla', $ancla);
            //return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            $seccion = Seccion::find(Input::get('seccion_id'));

            $menu = $seccion->menuSeccion()->lang()->url;
            $ancla = '#' . $seccion->estado . $seccion->id;

            return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
        }
    }

    public function vistaEditar($id, $next, $seccion_next) {

        //Me quedo con el item, buscando por id
        $item = Item::find($id);

        $portfolio_completo = PortfolioCompleto::find($item->portfolio()->portfolio_completo()->id);
        $secciones = parent::seccionesDinamicas();

        if ($portfolio_completo) {
            $this->array_view['item'] = $portfolio_completo->portfolio_simple()->item();
            $this->array_view['portfolio_completo'] = $portfolio_completo;
            $this->array_view['secciones'] = $secciones;
            $this->array_view['continue'] = $next;
            $this->array_view['seccion_next'] = $seccion_next;
            
            $seccion = Seccion::find($seccion_next);
        
            $modulo = $seccion->menuSeccion()->modulo();
            
            $this->array_view['menues'] = $modulo->menus;
            
            $this->array_view['titulo_texto'] = 'Editar obra';
            $this->array_view['titulo_modulo_imagen'] = 'Imágenes de la obra';

            $this->array_view['modulo_pagina_nombre'] = $modulo->nombre;
            return View::make($this->folder_name . '.editar', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
        }
    }

    public function editar() {

        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = PortfolioCompleto::editar(Input::all());

        /*
          if ($respuesta['error'] == true) {
          return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
          } else {
          return Redirect::to('admin/producto')->with('mensaje', $respuesta['mensaje']);
          }
         * 
         */
        if ($respuesta['error'] == true) {
            return Redirect::to($this->array_view['prefijo'].'/admin/' . $this->folder_name . '/editar/' . Input::get('portfolio_completo_id'))->with('mensaje', $respuesta['mensaje'])->with('error', true);
            //return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            if (Input::get('continue') == "home") {
                return Redirect::to('/')->with('mensaje', $respuesta['mensaje']);
            } else {
                $seccion = Seccion::find(Input::get('seccion_id'));

                $menu = $seccion->menuSeccion()->lang()->url;
                $ancla = '#' . $seccion->estado . $seccion->id;

                return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
            }
        }
    }

    public function borrar() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Item::borrarItem(Input::all());

        return $respuesta;
    }

    public function borrarPortfolioCompletoSeccion() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Item::borrarItemSeccion(Input::all());

        return $respuesta;
    }

    /*
      public function vistaDestacar($id, $next) {

      //Me quedo con el item, buscando por id
      $producto = Producto::find($id);

      if ($producto) {
      $marcas_principales = Marca::where('tipo', 'P')->where('estado', 'A')->get();
      $marcas_secundarias = Marca::where('tipo', 'S')->where('estado', 'A')->get();

      $this->array_view['marcas_principales'] = $marcas_principales;
      $this->array_view['marcas_secundarias'] = $marcas_secundarias;

      $this->array_view['item'] = $producto->item();
      $this->array_view['producto'] = $producto;
      $this->array_view['continue'] = $next;
      return View::make('producto.destacar', $this->array_view);
      } else {
      $this->array_view['texto'] = 'Error al cargar la página.';
      return View::make($this->project_name . '-error', $this->array_view);
      }
      }
     * 
     */

    public function destacar() {

        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = PortfolioCompleto::destacar(Input::all());

        /*
          if ($respuesta['error'] == true) {
          return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
          } else {
          return Redirect::to('admin/producto')->with('mensaje', $respuesta['mensaje']);
          }
         * 
         */
        if ($respuesta['error'] == true) {
            return Redirect::to('admin/' . $this->folder_name)->withErrors($respuesta['mensaje'])->withInput();
        } else {
            if (Input::get('continue') == "home") {
                return Redirect::to('/')->with('mensaje', $respuesta['mensaje']);
            } else {
                $menu = $respuesta['data']->portfolio_simple()->item()->seccionItem()->menuSeccion()->url;
                $ancla = '#' . $respuesta['data']->portfolio_simple()->item()->seccionItem()->estado . $respuesta['data']->portfolio_simple()->item()->seccionItem()->id;

                return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla);
            }
        }
    }

    /*
      public function consultarProductoLista() {

      //Me quedo con el item, buscando por id
      $producto = Producto::find(Input::get('producto_consulta_id'));

      if ($producto) {

      $data = Input::all();
      $this->array_view['item'] = $producto->item();
      $this->array_view['producto'] = $producto;
      $this->array_view['data'] = $data;

      Mail::send('emails.consulta-producto-listado', $this->array_view, function($message) use($data) {
      $message->from($data['email'], $data['nombre'])
      ->to('info@coarse.com.ar')
      ->subject('Consulta de producto')
      ;
      });

      if (count(Mail::failures()) > 0) {
      $mensaje = 'El mail no pudo enviarse.';
      } else {
      $mensaje = 'El mail se envió correctamente';
      }

      if (isset($data['continue']) && ($data['continue'] != "")) {
      switch ($data['continue']) {
      case "detalle":
      return Redirect::to('producto/' . $producto->item()->url)->with('mensaje', $mensaje);
      break;
      case "seccion":
      $menu = $producto->item()->seccionItem()->menuSeccion()->url;
      $ancla = '#' . $producto->item()->seccionItem()->estado . $producto->item()->seccionItem()->id;

      return Redirect::to('menu/' . $menu)->with('mensaje', $mensaje)->with('ancla', $ancla);
      break;
      }
      }

      return Redirect::to('/')->with('mensaje', $mensaje);
      //return View::make('producto.editar', $this->array_view);
      } else {
      $this->array_view['texto'] = 'Error al cargar la página.';
      return View::make($this->project_name . '-error', $this->array_view);
      }
      }

      public function consultaGeneral() {

      $data = Input::all();
      $this->array_view['data'] = $data;

      Mail::send('emails.consulta-general', $this->array_view, function($message) use($data) {
      $message->from($data['email'], $data['nombre'])
      ->to('info@coarse.com.ar')
      ->subject('Consulta')
      ;
      });

      if (count(Mail::failures()) > 0) {
      $mensaje = 'El mail no pudo enviarse.';
      } else {
      $mensaje = 'El mail se envió correctamente';
      }

      if (isset($data['continue']) && ($data['continue'] != "")) {
      switch ($data['continue']) {
      case "contacto":
      return Redirect::to('contacto')->with('mensaje', $mensaje);
      break;
      case "menu":
      $menu = Menu::find($data['menu_id']);

      return Redirect::to('menu/' . $menu->url)->with('mensaje', $mensaje);
      break;
      }
      }

      return Redirect::to("/")->with('mensaje', $mensaje);
      //return View::make('producto.editar', $this->array_view);
      }
     * 
     */
}
