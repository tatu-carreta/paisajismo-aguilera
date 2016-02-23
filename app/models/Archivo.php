<?php

class Archivo extends Eloquent {

    //Tabla de la BD
    protected $table = 'archivo';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre', 'titulo', 'carpeta', 'tipo', 'ampliada', 'estado', 'fecha_carga', 'fecha_modificacion', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public static function agregar($info) {

        $respuesta = array();

        $rules = array(
            'archivo' => array('mimes:pdf'),
        );

        $validator = Validator::make($info, $rules);

        if ($validator->fails()) {
            //return Response::make($validator->errors->first(), 400);
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = 'no pasa';
        } else {


            $file = $info['archivo'];

            $count = count($file->getClientOriginalName()) - 4;

            $nombreArchivo = Str::limit(Str::slug($file->getClientOriginalName()), $count, "");
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
            //$extension = File::extension($file['name']);

            $carpeta = '/uploads/archivos/';
            $directory = public_path() . $carpeta;
            //$filename = sha1(time() . Hash::make($filename) . time()) . ".{$extension}";
            //Pregunto para que no se repita el nombre de la imagen
            if (!is_null(Archivo::archivoPorNombre($nombreArchivo . ".{$extension}"))) {

                $filename = $nombreArchivo . "(" . Str::limit(sha1(time()), 3, "") . ")" . ".{$extension}";
            } else {
                $filename = $nombreArchivo . ".{$extension}";
            }

            //$upload_success = $file->move($directory, $filename);

            if ($file->move($directory, $filename)) {
                $datos = array(
                    'nombre' => $filename,
                    'titulo' => $nombreArchivo . ".{$extension}",
                    'carpeta' => $carpeta,
                    'tipo' => "{$extension}",
                    'estado' => 'A',
                    'fecha_carga' => date("Y-m-d H:i:s"),
                    'usuario_id_carga' => Auth::user()->id
                );

                $archivo = static::create($datos);

                //Mensaje correspondiente a la agregacion exitosa
                $respuesta['mensaje'] = 'Archivo creado.';
                $respuesta['error'] = false;
                $respuesta['data'] = $archivo;
                //return Response::json('success', 200);
            } else {
                //Mensaje correspondiente a la agregacion exitosa
                $respuesta['mensaje'] = 'Archivo erróneo.';
                $respuesta['error'] = true;
                $respuesta['data'] = null;
                //return Response::json('error', 400);
            }
        }

        return $respuesta;
    }

    public static function editar($input) {
        $respuesta = array();

        $reglas = array(
            'id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $archivo = Archivo::find($input['id']);

            $archivo->titulo = $input['titulo'];
            $archivo->fecha_modificacion = date("Y-m-d H:i:s");

            $archivo->save();

            $respuesta['mensaje'] = 'Archivo modificado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $archivo;
        }

        return $respuesta;
    }

    public static function borrar($input) {
        $respuesta = array();

        $reglas = array(
            'id' => array('integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $archivo = Archivo::find($input['id']);

            $archivo->fecha_baja = date("Y-m-d H:i:s");
            $archivo->estado = 'B';
            $archivo->usuario_id_baja = Auth::user()->id;

            $archivo->save();

            $respuesta['mensaje'] = 'Archivo eliminado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $archivo;
        }

        return $respuesta;
    }

    //Me quedo con las categorias a las que pertenece el Item
    public function items() {
        return $this->belongsToMany('Item', 'item_archivo', 'item_id', 'archivo_id');
    }

    public static function archivoPorNombre($nombre) {
        return DB::table('archivo')
                        ->where('nombre', $nombre)
                        ->first();
    }

}
