<?php

class CarritoController extends BaseController {

    protected $folder_name = 'carrito';

    public function vistaListado() {

        //Hace que se muestre el html lista.blade.php de la carpeta categoria
        //con los parametros pasados por el array
        return View::make($this->folder_name . '.ver-' . $this->folder_name, $this->array_view);
    }

    public function agregarProducto($producto_id, $continue, $sec_id) {

        if (Cart::count(false) < 6) {

            $info = array(
                'producto_id' => $producto_id
            );

            //Aca se manda a la funcion borrarItem de la clase Item
            //y se queda con la respuesta para redirigir cual sea el caso
            $respuesta = Carrito::agregarProducto($info);

            if ($respuesta['error']) {
                $estado = 'error';
                $error = true;
                $producto_carrito_subido = false;
            } else {
                $estado = 'ok';
                $error = false;
                $producto_carrito_subido = true;
            }
        } else {
            $respuesta['mensaje'] = Lang::get('controllers.carrito.maximo_permitido', ['cant' => 6]);
            $estado = 'error';
            $error = true;
            $producto_carrito_subido = false;
        }

        $producto = Producto::find($producto_id);

        switch ($continue) {
            case 'home':
                $anclaProd = '#Pr' . $producto->id;

                if ($error) {
                    return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('anclaProd', $anclaProd);
                } else {
                    return Redirect::to('/')->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                }

                break;
            case 'seccion':

                if ($sec_id != "") {
                    $seccion = Seccion::find($sec_id);

                    if ($seccion) {

                        $menu = $seccion->menuSeccion()->lang()->url;
                        $ancla = '#' . $producto->item()->seccionItem()->estado . $producto->item()->seccionItem()->id;

                        $anclaProd = '#Pr' . $producto->id;

                        if ($error) {
                            return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('anclaProd', $anclaProd);
                        } else {
                            return Redirect::to('/' . $menu)->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                        }
                        //return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with($estado, $error)->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido)->with('anclaProd', $anclaProd);
                    } else {
                        if ($error) {
                            return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('anclaProd', $anclaProd);
                        } else {
                            return Redirect::to('/')->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                        }
                        //return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                    }
                } else {
                    if ($error) {
                        return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('anclaProd', $anclaProd);
                    } else {
                        return Redirect::to('/')->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                    }
                    //return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                }

                break;
            case 'carrito':
                if ($error) {
                    return Redirect::to('/carrito')->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('anclaProd', $anclaProd);
                } else {
                    return Redirect::to('/carrito')->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                }
                //return Redirect::to('/carrito')->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                break;
            default :
                if ($error) {
                    return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('anclaProd', $anclaProd);
                } else {
                    return Redirect::to('/')->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                }
                //return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with($estado, $error)->with('producto_carrito', $producto)->with('producto_carrito_subido', $producto_carrito_subido);
                break;
        }
    }

    public function editarProducto($producto_id, $rowId) {

        $info = array(
            'producto_id' => $producto_id,
            'rowId' => $rowId,
            'cantidad' => Input::get('cantidad')
        );

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Carrito::editarProducto($info);

        return $respuesta;
    }

    public function borrarProducto($producto_id, $rowId, $continue, $sec_id) {

        $info = array(
            'producto_id' => $producto_id,
            'rowId' => $rowId
        );

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Carrito::borrarProducto($info);

        $producto = Producto::find($producto_id);
        switch ($continue) {
            case 'home':
                $anclaProd = '#Pr' . $producto->id;

                return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with('ok', true)->with('anclaProd', $anclaProd);
                break;
            case 'seccion':
                if ($sec_id != "") {
                    $seccion = Seccion::find($sec_id);

                    if ($seccion) {

                        $menu = $seccion->menuSeccion()->lang()->url;
                        $ancla = '#' . $producto->item()->seccionItem()->estado . $producto->item()->seccionItem()->id;

                        $anclaProd = '#Pr' . $producto->id;

                        return Redirect::to('/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla)->with('ok', true)->with('anclaProd', $anclaProd);
                    } else {
                        return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with('ok', true);
                    }
                } else {
                    return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with('ok', true);
                }
                break;
            case 'carrito':
                return Redirect::to('/carrito')->with('mensaje', $respuesta['mensaje'])->with('ok', true);
                break;
            default :
                $anclaProd = '#Pr' . $producto->id;

                return Redirect::to('/')->with('mensaje', $respuesta['mensaje'])->with('ok', true)->with('anclaProd', $anclaProd);
                break;
        }
        //return Redirect::to('/carrito')->with('mensaje', $respuesta['mensaje']);
    }

    public function borrar() {

        //Aca se manda a la funcion borrarItem de la clase Item
        //y se queda con la respuesta para redirigir cual sea el caso
        $respuesta = Carrito::borrar();

        return Redirect::to('/' . $this->folder_name)->with('mensaje', $respuesta['mensaje']);
    }

}
