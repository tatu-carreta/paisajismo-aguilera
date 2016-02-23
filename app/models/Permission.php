<?php

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission {

    protected $fillable = array('name');
    public $timestamps = false;

    public static function agregar($input) {

        $permission = static::create(['name' => $input['nombre']]);

        if ($permission) {
            $respuesta['error'] = false;
            $respuesta['mensaje'] = "Permiso creado.";
            $respuesta['data'] = $permission;
        } else {
            $respuesta['error'] = true;
            $respuesta['mensaje'] = "Error al crear el permiso.";
        }

        return $respuesta;
    }

    public static function asignarRoles($input) {

        DB::table('permission_role')->truncate();
        foreach ($input['perfiles'] as $key => $perfil) {
            $ids = explode("|", $perfil);
            echo "PERFIL -> " . $ids[0];
            echo "PERMISO -> " . $ids[1] . "<br>";
            DB::table('permission_role')->insert(['permission_id' => $ids[1], 'role_id' => $ids[0]]);
        }

        $respuesta['error'] = false;
        $respuesta['mensaje'] = "Permisos asignados.";
        
        return $respuesta;
    }

}
