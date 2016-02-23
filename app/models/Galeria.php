<?php

class Galeria extends Item {

    //Tabla de la BD
    protected $table = 'galeria';
    //Atributos que van a ser modificables
    protected $fillable = array('item_id');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    //Función de Agregación de Item
    public static function agregar($input) {
        //Lo crea definitivamente

        $item = Item::agregarItem($input);

        $galeria = static::create(['item_id' => $item['data']->id]);

        if ($galeria) {
            $respuesta['error'] = false;
            $respuesta['mensaje'] = "Galería creada.";
            $respuesta['data'] = $galeria;
        } else {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = "La galería no pudo ser creada. Compruebe los campos.";
        }

        return $respuesta;
    }

    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
            'id' => array('required')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {
            
            $galeria = Galeria::find($input['id']);

            if (isset($input['imagenes_existentes']) && ($input['imagenes_existentes'] != "")) {
                foreach ($input['imagenes_existentes'] as $key => $imagen) {
                    if ($imagen != "") {
                        $data_imagen = array(
                            'id' => $imagen,
                            'epigrafe' => $input['epigrafes_existentes'][$key]
                        );
                        $imagen_creada = Imagen::editar($data_imagen);
                    }
                }
            }


            $data_item = array(
                'id' => $galeria->item_id,
                'titulo' => $input['titulo'],
                'descripcion' => $input['descripcion'],
                'file' => $input['file'],
                'epigrafe' => $input['epigrafe']
            );


            $item = Item::editarItem($data_item);

            $respuesta['mensaje'] = 'Galeria modificada.';
            $respuesta['error'] = false;
            $respuesta['data'] = $item['data'];
        }

        return $respuesta;
    }

    public function item() {
        return Item::find($this->item_id);
    }

    public static function buscar($item_id) {
        return Galeria::where('item_id', $item_id)->first();
    }

}
