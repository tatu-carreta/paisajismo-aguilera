<?php

// se debe indicar en donde esta la interfaz a implementar
use Illuminate\Auth\UserInterface;
use Zizaco\Entrust\HasRole;

Class Usuario extends Eloquent implements UserInterface {

    use HasRole;

    protected $table = 'usuario';
    protected $fillable = array('nombre', 'clave', 'iderUser', 'estado', 'ultimo_ingreso', 'fecha_carga', 'fecha_modificacion', 'fecha_baja', 'remember_token');
    public $timestamps = false;

    // este metodo se debe implementar por la interfaz
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    //este metodo se debe implementar por la interfaz
    // y sirve para obtener la clave al momento de validar el inicio de sesión 
    public function getAuthPassword() {
        return $this->clave;
    }

    public function getRememberToken() {
        return $this->remember_token;
    }

    public function getRememberTokenName() {
        return 'remember_token';
    }

    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    public static function registrarUsuario($input) {
        $respuesta = array();

        $reglas = array(
            'nombre' => array('required', 'max:50'),
            'clave' => array('required', 'max:50'),
            'perfil_id' => array('required', 'integer')
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            $datos = array(
                'nombre' => $input['nombre'],
                'clave' => Hash::make($input['clave']),
                'estado' => 'A',
                'fecha_carga' => date("Y-m-d H:i:s"),
            );

            $usuario = static::create($datos);

            $usuario->perfiles()->attach($input['perfil_id']);

            $respuesta['mensaje'] = 'Usuario registrado.';
            $respuesta['error'] = false;
            $respuesta['data'] = $usuario;
        }

        return $respuesta;
    }

    public static function loguearse($input) {
        $respuesta = array();

        $reglas = array(
            'nombre' => array('required', 'max:50'),
            'password' => array('required', 'max:50'),
        );

        $validator = Validator::make($input, $reglas);

        if ($validator->fails()) {
            $respuesta['mensaje'] = $validator;
            $respuesta['error'] = true;
        } else {

            if (Auth::attempt($input)) {

                $usuario = Usuario::find(Auth::user()->id);

                $usuario->ultimo_ingreso = date("Y-m-d H:i:s");

                $usuario->save();

                $datos = array(
                    "usuario_id" => $usuario->id,
                    "fecha_acceso" => date("Y-m-d H:i:s"),
                    "ip" => $_SERVER['REMOTE_ADDR']
                );

                DB::table('usuario_acceso')->insert($datos);

                $respuesta['mensaje'] = 'Usuario logueado.';
                $respuesta['error'] = false;
                $respuesta['data'] = $usuario;
            } else {

                $respuesta['mensaje'] = 'Alguno de los datos es incorrecto. Vuelva a intentarlo.';
                $respuesta['error'] = true;
            }
        }

        return $respuesta;
    }

    public function perfil() {
        $p = NULL;
        foreach ($this->perfiles as $perfil) {
            $p = $perfil;
        }
        return $p;
    }

    public function perfiles() {
        return $this->belongsToMany('Role', 'assigned_roles', 'user_id', 'role_id');
    }

}