<?php

class ProductoController extends BaseController {

    protected $folder_name = 'producto';

    public function vistaListado() {

        $items_borrados = Item::where('estado', 'B')->lists('id');

        if (count($items_borrados) > 0) {

            $productos = Producto::whereNotIn('item_id', $items_borrados)->get();
        } else {
            $productos = Producto::all();
        }
        $categorias = Categoria::where('estado', 'A')->get();
        $secciones = Seccion::where('estado', 'A')->get();

        $this->array_view['productos'] = $productos;
        $this->array_view['categorias'] = $categorias;
        $this->array_view['secciones'] = $secciones;

        //Hace que se muestre el html lista.blade.php de la carpeta item
        //con los parametros pasados por el array
        return View::make($this->folder_name . '.lista', $this->array_view);
    }

    public function mostrarInfoProducto($url) {
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

        $this->array_view['seccion'] = $seccion;

        $marcas_principales = Marca::where('tipo', 'P')->where('estado', 'A')->orderBy('nombre')->get();
        $marcas_secundarias = Marca::where('tipo', 'S')->where('estado', 'A')->orderBy('nombre')->get();

        $this->array_view['marcas_principales'] = $marcas_principales;
        $this->array_view['marcas_secundarias'] = $marcas_secundarias;

        $this->array_view['titulo_texto'] = 'Nuevo producto';
        $this->array_view['modulo_pagina_nombre'] = $modulo->nombre;
        
        $this->array_view['placeholder_nombre'] = 'Código';
        $this->array_view['max_length'] = 9;

        return View::make($this->folder_name . '.agregar', $this->array_view);
    }

    public function agregar() {

        //Aca se manda a la funcion agregarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Producto::agregarProducto(Input::all());

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

            $menu = $seccion->menuSeccion()->url;
            $ancla = '#' . $seccion->estado . $seccion->id;

            return Redirect::to($this->array_view['prefijo'].'/admin/' . $this->folder_name . '/agregar/' . $seccion->id)->with('mensaje', $respuesta['mensaje'])->with('error', true);
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
        $producto = Producto::find($id);

        if ($producto) {
            $marcas_principales = Marca::where('tipo', 'P')->where('estado', 'A')->orderBy('nombre')->get();
            $marcas_secundarias = Marca::where('tipo', 'S')->where('estado', 'A')->orderBy('nombre')->get();

            $this->array_view['marcas_principales'] = $marcas_principales;
            $this->array_view['marcas_secundarias'] = $marcas_secundarias;

            $seccion = $producto->item()->seccionItem();

            $modulo = $seccion->menuSeccion()->modulo();

            $this->array_view['menues'] = $modulo->menus;

            $this->array_view['item'] = $producto->item();
            $this->array_view['producto'] = $producto;
            $this->array_view['continue'] = $next;
            $this->array_view['seccion_next'] = $seccion_next;
            
            $this->array_view['titulo_texto'] = 'Editar producto';
            $this->array_view['modulo_pagina_nombre'] = $modulo->nombre;

            $this->array_view['placeholder_nombre'] = 'Código';
            $this->array_view['max_length'] = 9;
            return View::make($this->folder_name . '.editar', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            //return View::make($this->project_name . '-error', $this->array_view);
            return Redirect::to('/');
        }
    }

    public function editar() {

        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Producto::editar(Input::all());

        /*
          if ($respuesta['error'] == true) {
          return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
          } else {
          return Redirect::to('admin/producto')->with('mensaje', $respuesta['mensaje']);
          }
         * 
         */
        if (Input::get('seccion_id') !== null) {
            $seccion_id = Input::get('seccion_id');
        } else {
            $seccion_id = 'null';
        }

        if ($respuesta['error'] == true) {
            return Redirect::to($this->array_view['prefijo'].'/admin/' . $this->folder_name . '/editar/' . Input::get('producto_id') . '/' . Input::get('continue') . '/' . $seccion_id)->with('mensaje', $respuesta['mensaje'])->with('error', true);
            //return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
        } else {
            if (Input::get('continue') == "home") {
                $anclaProd = '#Pr' . $respuesta['data']->id;

                return Redirect::to($this->array_view['prefijo'].'/')->with('mensaje', $respuesta['mensaje'])->with('ok', true)->with('anclaProd', $anclaProd);
            } else {
                $seccion = Seccion::find(Input::get('seccion_id'));

                $menu = $seccion->menuSeccion()->lang()->url;
                $ancla = '#' . $seccion->estado . $seccion->id;

                $anclaProd = '#Pr' . $respuesta['data']->id;
                return Redirect::to($this->array_view['prefijo'].'/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true)->with('anclaProd', $anclaProd);
            }
        }
    }

    public function borrar() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Item::borrarItem(Input::all());

        return $respuesta;
    }

    public function borrarProductoSeccion() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Item::borrarItemSeccion(Input::all());

        return $respuesta;
    }

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
            return View::make($this->folder_name . '.destacar', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
            //return Redirect::to('/');
        }
    }

    public function destacar() {

        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Producto::destacar(Input::all());

        /*
          if ($respuesta['error'] == true) {
          return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
          } else {
          return Redirect::to('admin/producto')->with('mensaje', $respuesta['mensaje']);
          }
         * 
         */
        if ($respuesta['error'] == true) {
            return Redirect::to('admin/' . $this->folder_name)->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            if (Input::get('continue') == "home") {
                return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with('ok', true);
            } else {
                $menu = $respuesta['data']->item()->seccionItem()->menuSeccion()->url;
                $ancla = '#' . $respuesta['data']->item()->seccionItem()->estado . $respuesta['data']->item()->seccionItem()->id;

                return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true);
            }
        }
    }

    public function nuevo() {

        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Producto::ponerNuevo(Input::all());

        /*
          if ($respuesta['error'] == true) {
          return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
          } else {
          return Redirect::to('admin/producto')->with('mensaje', $respuesta['mensaje']);
          }
         * 
         */
        /*
          if ($respuesta['error'] == true) {
          return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
          } else {
          if (Input::get('continue') == "home") {
          return Redirect::to('/')->with('mensaje', $respuesta['mensaje']);
          } else {
          $seccion = Seccion::find(Input::get('seccion_id'));

          $menu = $seccion->menuSeccion()->url;
          $ancla = '#' . $seccion->estado . $seccion->id;

          return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla);
          }
          }
         * 
         */
        return $respuesta;
    }

    public function vistaOferta($id, $seccion_id, $next) {

        //Me quedo con el item, buscando por id
        $producto = Producto::find($id);

        if ($producto) {
            $this->array_view['seccion_id'] = $seccion_id;

            $this->array_view['item'] = $producto->item();
            $this->array_view['producto'] = $producto;
            $this->array_view['continue'] = $next;
            return View::make($this->folder_name . '.oferta', $this->array_view);
        } else {
            $this->array_view['texto'] = Lang::get('controllers.error_carga_pagina');
            return View::make($this->project_name . '-error', $this->array_view);
            //return Redirect::to('/');
        }
    }

    public function oferta() {

        //Aca se manda a la funcion editarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Producto::ponerOferta(Input::all());

        /*
          if ($respuesta['error'] == true) {
          return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
          } else {
          return Redirect::to('admin/producto')->with('mensaje', $respuesta['mensaje']);
          }
         * 
         */
        if ($respuesta['error'] == true) {
            return Redirect::to('admin/' . $this->folder_name); //->with('mensaje', $respuesta['mensaje'])->with('error', true);
        } else {
            if (Input::get('continue') == "home") {
                $anclaProd = '#Pr' . $respuesta['data']->id;
                return Redirect::to('/')->with('anclaProd', $anclaProd); //->with('mensaje', $respuesta['mensaje'])->with('ok', true);
            } else {
                $seccion = Seccion::find(Input::get('seccion_id'));
                $menu = $seccion->menuSeccion()->url;
                $ancla = '#' . $seccion->estado . $seccion->id;

                $anclaProd = '#Pr' . $respuesta['data']->id;
                return Redirect::to('/' . $menu)->with('ancla', $ancla)->with('anclaProd', $anclaProd); //->with('mensaje', $respuesta['mensaje'])->with('ok', true);
            }
        }
    }

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

                        return Redirect::to('/' . $menu)->with('mensaje', $mensaje)->with('ancla', $ancla);
                        break;
                }
            }

            return Redirect::to('/')->with('mensaje', $mensaje);
            //return View::make('producto.editar', $this->array_view);
        } else {
            $this->array_view['texto'] = 'Error al cargar la página.';
            return View::make($this->project_name . '-error', $this->array_view);
            //return Redirect::to('/');
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

                    return Redirect::to('/' . $menu->url)->with('mensaje', $mensaje);
                    break;
            }
        }

        return Redirect::to("/")->with('mensaje', $mensaje);
        //return View::make('producto.editar', $this->array_view);
    }

}
