<?php

class ClienteController extends BaseController {

    public function registrar() {
        $input = Input::all();

        $respuesta = Cliente::agregar($input);
        /*
          if ($respuesta['error'] == true) {
          return Redirect::to('admin/producto/agregar/' . $seccion->id)->with('mensaje', $respuesta['mensaje']); //->with('ancla', $ancla);
          //return Redirect::to('admin/producto')->withErrors($respuesta['mensaje'])->withInput();
          } else {
          $menu = $respuesta['data']->item()->seccionItem()->menuSeccion()->url;
          $ancla = '#' . $respuesta['data']->item()->seccionItem()->estado . $respuesta['data']->item()->seccionItem()->id;

          return Redirect::to('menu/' . $menu)->with('mensaje', $respuesta['mensaje'])->with('ancla', $ancla);
          }
         * 
         */
        return Redirect::back()->with('mensaje', $respuesta['mensaje']);
    }

    public function consultaContacto() {

        $data = Input::all();

        Input::flashOnly('nombre', 'email', 'telefono', 'consulta');

        $reglas = array(
            'email' => array('required', 'email'),
            'nombre' => array('required'),
            //'telefono' => array('required'),
        );

        $validator = Validator::make($data, $reglas);

        if ($validator->fails()) {
            $messages = $validator->messages();
            if ($messages->has('nombre')) {
                $mensaje = $messages->first('nombre');
            } elseif ($messages->has('email')) {
                $mensaje = $messages->first('email');
            } /*elseif ($messages->has('telefono')) {
                $mensaje = $messages->first('telefono');
            } */else {
                $mensaje = Lang::get('controllers.cliente.datos_consulta_contacto_incorrectos');
            }

            return Redirect::to('/contacto')->with('mensaje', $mensaje)->with('error', true)->withInput();
        } else {

        $this->array_view['data'] = $data;

        Mail::send('emails.consulta-contacto', $this->array_view, function($message) use($data) {
            $message->from($data['email'], $data['nombre'])
                    ->to('contactos@paisajismoaguilera.com.ar')
                    ->subject('Consulta')
            ;
        });

        if (count(Mail::failures()) > 0) {
            $mensaje = Lang::get('controllers.cliente.consulta_no_enviada');
        } else {

                $datos_persona = array(
                    'email' => $data['email'],
                    'apellido' => $data['nombre'],
                    'tipo_telefono_id' => 2,
                    'telefono' => $data['telefono']
                );

                Persona::agregar($datos_persona);

            $mensaje = Lang::get('controllers.cliente.consulta_enviada');
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

    public function exportarEmail() {
        Excel::create('Clientes' . date('Ymd'), function($excel) {
            $excel->sheet('Clientes', function($sheet) {

                $clientes = Cliente::select('email')->distinct()->get();

                $datos = array(
                    array('Email'),
                );

                foreach ($clientes as $cliente) {
                    array_push($datos, array($cliente->email));
                }

                $sheet->fromModel($datos, null, 'A1', false, false);

                $sheet->row(1, function($row) {

                    // call cell manipulation methods
                    $row->setFontWeight('bold');
                });
            });
        })->download('xls');
    }

}
