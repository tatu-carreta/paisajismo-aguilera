<?php

class Video extends Eloquent {

    //Tabla de la BD
    protected $table = 'video';
    //Atributos que van a ser modificables
    protected $fillable = array('nombre', 'url', 'tipo', 'ampliada', 'estado', 'fecha_carga', 'fecha_baja', 'usuario_id_carga', 'usuario_id_baja');
    //Hace que no se utilicen los default: create_at y update_at
    public $timestamps = false;

    public static function agregarYoutube($info) {

        $respuesta = array();

        $rules = array(
                //'archivo' => array('mimes:pdf'),
        );

        $validator = Validator::make($info, $rules);

        if ($validator->fails()) {
            //return Response::make($validator->errors->first(), 400);
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = 'no pasa';
        } else {

            //$INFO_video = Youtube::getVideoInfo($info['ID_video']);


            $datos = array(
                'nombre' => '',
                'url' => $info['ID_video'],
                'tipo' => 'youtube',
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            $video = static::create($datos);

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = 'Video creado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $video;
            //return Response::json('success', 200);
        }

        return $respuesta;
    }
    
    public static function agregarVimeo($info) {

        $respuesta = array();

        $rules = array(
                //'archivo' => array('mimes:pdf'),
        );

        $validator = Validator::make($info, $rules);

        if ($validator->fails()) {
            //return Response::make($validator->errors->first(), 400);
            //Si está todo mal, carga lo que corresponde en el mensaje.
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = 'no pasa';
        } else {

            //$INFO_video = Youtube::getVideoInfo($info['ID_video']);


            $datos = array(
                'nombre' => '',
                'url' => $info['ID_video'],
                'tipo' => 'vimeo',
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
                'usuario_id_carga' => Auth::user()->id
            );

            $video = static::create($datos);

            //Mensaje correspondiente a la agregacion exitosa
            $respuesta['mensaje'] = 'Video creado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $video;
            //return Response::json('success', 200);
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

            $video = Video::find($input['id']);

            $video->fecha_baja = date("Y-m-d H:i:s");
            $video->estado = 'B';
            $video->usuario_id_baja = Auth::user()->id;

            $video->save();

            $respuesta['mensaje'] = 'Video eliminado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $video;
        }

        return $respuesta;
    }
    /*
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
     * 
     */

    public static function validarUrl($url, $hosts, $paths) {
        $dataUrl = parse_url($url);
        $ok = false;
        $texto = '';
        /*
         * Array
          (
          [scheme] => http
          [host] => youtube.com
          [path] => /watch
          [query] => v=VIDEOID
          )
         */
        if (!isset($dataUrl['scheme']) || ($dataUrl['scheme'] == "") || (!in_array($dataUrl['scheme'], array('http', 'https')))) {
            $texto = 'Problema en el protocolo.';
        } elseif (!isset($dataUrl['host']) || ($dataUrl['host'] == '') || (!in_array($dataUrl['host'], $hosts))) {
            $texto = 'Problema en el hosting.';
        } elseif (!isset($dataUrl['path']) || ($dataUrl['path'] == '') || (!in_array($dataUrl['path'], $paths))) {
            $texto = 'Problema en la url.';
        } elseif (!isset($dataUrl['query']) || ($dataUrl['query'] == '')) {
            $texto = 'Problema en la url.';
        } else {
            $ok = true;
        }

        $result = array(
            'estado' => $ok,
            'texto' => $texto
        );

        return $result;
    }
    
    public static function validarUrlVimeo($url, $hosts) {
        $dataUrl = parse_url($url);
        $ok = false;
        $texto = '';
        /*
         * Array
          (
          [scheme] => http
          [host] => youtube.com
          [path] => /watch
          [query] => v=VIDEOID
          )
         */
        if (!isset($dataUrl['scheme']) || ($dataUrl['scheme'] == "") || (!in_array($dataUrl['scheme'], array('http', 'https')))) {
            $texto = 'Problema en el protocolo.';
        } elseif (!isset($dataUrl['host']) || ($dataUrl['host'] == '') || (!in_array($dataUrl['host'], $hosts))) {
            $texto = 'Problema en el hosting.';
        } elseif (!isset($dataUrl['path']) || ($dataUrl['path'] == '')) {
            $texto = 'Problema en la url.';
        } else {
            $ok = true;
        }

        $result = array(
            'estado' => $ok,
            'texto' => $texto
        );

        return $result;
    }

    //Me quedo con las categorias a las que pertenece el Item
    public function items() {
        return $this->belongsToMany('Item', 'item_video', 'item_id', 'video_id');
    }

    public static function videoPorNombre($nombre) {
        return DB::table('video')
                        ->where('nombre', $nombre)
                        ->first();
    }

}
